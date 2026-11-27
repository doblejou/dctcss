<?php

declare(strict_types=1);

function ip_is_in_cidr(string $ip, string $cidr): bool
{
    [$network, $prefixLength] = explode('/', $cidr, 2);
    $addressBytes = inet_pton($ip);
    $networkBytes = inet_pton($network);
    if ($addressBytes === false || $networkBytes === false || strlen($addressBytes) !== strlen($networkBytes)) {
        return false;
    }
    $bits = (int) $prefixLength;
    $wholeBytes = intdiv($bits, 8);
    $remainingBits = $bits % 8;
    if (substr($addressBytes, 0, $wholeBytes) !== substr($networkBytes, 0, $wholeBytes)) {
        return false;
    }
    if ($remainingBits === 0) {
        return true;
    }
    $mask = (0xff << (8 - $remainingBits)) & 0xff;
    return (ord($addressBytes[$wholeBytes]) & $mask) === (ord($networkBytes[$wholeBytes]) & $mask);
}

function is_public_ip(string $ip): bool
{
    $blockedRanges = str_contains($ip, ':') ? [
        '::/128',
        '::1/128',
        '::ffff:0:0/96',
        '64:ff9b:1::/48',
        '100::/64',
        '2001::/23',
        '2001:db8::/32',
        '2002::/16',
        '3fff::/20',
        'fc00::/7',
        'fe80::/10',
        'ff00::/8',
    ] : [
        '0.0.0.0/8',
        '10.0.0.0/8',
        '100.64.0.0/10',
        '127.0.0.0/8',
        '169.254.0.0/16',
        '172.16.0.0/12',
        '192.0.0.0/24',
        '192.0.2.0/24',
        '192.88.99.0/24',
        '192.168.0.0/16',
        '198.18.0.0/15',
        '198.51.100.0/24',
        '203.0.113.0/24',
        '224.0.0.0/4',
        '240.0.0.0/4',
    ];
    foreach ($blockedRanges as $range) {
        if (ip_is_in_cidr($ip, $range)) {
            return false;
        }
    }
    return true;
}

function validate_site_url(string $siteUrl, string $environment = 'development'): string
{
    $parts = parse_url($siteUrl);
    if ($siteUrl === ''
        || trim($siteUrl) !== $siteUrl
        || str_ends_with($siteUrl, '/')
        || filter_var($siteUrl, FILTER_VALIDATE_URL) === false
        || $parts === false
        || !in_array($parts['scheme'] ?? '', ['http', 'https'], true)
        || ($parts['host'] ?? '') === ''
        || isset($parts['user'])
        || isset($parts['pass'])
        || isset($parts['query'])
        || isset($parts['fragment'])
        || ($parts['path'] ?? '') !== ''
    ) {
        fail('SITE_URL must be an absolute HTTP/HTTPS origin without credentials, path, query, fragment or trailing slash.');
    }
    if (!in_array($environment, ['development', 'production'], true)) {
        fail('DCTCSS_ENV must be development or production.');
    }
    if ($environment === 'production') {
        $host = strtolower((string) $parts['host']);
        $ipHost = trim($host, '[]');
        $ip = filter_var($ipHost, FILTER_VALIDATE_IP);
        if (($parts['scheme'] ?? '') !== 'https'
            || str_ends_with($host, '.')
            || $host === 'localhost'
            || str_ends_with($host, '.localhost')
            || str_ends_with($host, '.local')
            || (!str_contains($host, '.') && $ip === false)
            || ($ip !== false && !is_public_ip($ipHost))
        ) {
            fail('Production SITE_URL must use HTTPS and a non-local public host.');
        }
    }
    return $siteUrl;
}

function load_site_config(string $root): array
{
    $path = is_file("$root/.env") ? "$root/.env" : "$root/.env.example";
    $config = @parse_ini_file($path, false, INI_SCANNER_RAW);
    if (!is_array($config)) {
        fail("Cannot parse site configuration: $path");
    }
    exact_set(array_keys($config), ['DCTCSS_ENV', 'SITE_URL'], 'Site configuration keys');
    $environmentValue = getenv('DCTCSS_ENV');
    $siteUrlValue = getenv('SITE_URL');
    $environment = $environmentValue !== false ? $environmentValue : $config['DCTCSS_ENV'];
    $siteUrl = $siteUrlValue !== false ? $siteUrlValue : $config['SITE_URL'];
    if (!is_string($environment) || !is_string($siteUrl)) {
        fail('DCTCSS_ENV and SITE_URL must be strings.');
    }
    return ['environment' => $environment, 'siteUrl' => validate_site_url($siteUrl, $environment)];
}

function load_site_url(string $root): string
{
    return load_site_config($root)['siteUrl'];
}

function assert_managed_entries(string $directory, array $allowed, string $label): void
{
    foreach (directory_entries($directory) as $entry) {
        if (!in_array($entry, $allowed, true)) {
            fail("$label contains an unmanaged entry: $entry");
        }
        $path = "$directory/$entry";
        if (is_link($path)) {
            fail("$label must not contain symlinks: $entry");
        }
    }
}

function canonical_output_root(string $root, string $requested, bool $inspectContents = true): string
{
    if ($requested === '' || str_contains($requested, "\n") || str_contains($requested, "\r")) {
        fail('OUTPUT_ROOT must be a non-empty filesystem path.');
    }
    $projectRoot = realpath($root);
    if ($projectRoot === false) {
        fail('Cannot resolve the project root.');
    }
    if (file_exists($requested) || is_link($requested)) {
        if (!is_dir($requested) || is_link($requested)) {
            fail('Existing OUTPUT_ROOT must be a real directory.');
        }
        $candidate = realpath($requested);
    } else {
        $trimmed = rtrim($requested, DIRECTORY_SEPARATOR);
        $parent = realpath(dirname($trimmed));
        $name = basename($trimmed);
        if ($parent === false || $name === '' || $name === '.' || $name === '..') {
            fail('A new OUTPUT_ROOT requires an existing parent directory.');
        }
        $candidate = $parent . DIRECTORY_SEPARATOR . $name;
    }
    if ($candidate === false || $candidate === DIRECTORY_SEPARATOR) {
        fail('OUTPUT_ROOT must not be the filesystem root.');
    }
    if ($candidate !== $projectRoot && path_is_within($candidate, $projectRoot)) {
        fail('OUTPUT_ROOT must not be a project subdirectory.');
    }
    if (!$inspectContents || $candidate === $projectRoot || !is_dir($candidate)) {
        return $candidate;
    }

    assert_managed_entries($candidate, ['dist', 'public'], 'OUTPUT_ROOT');
    $manifest = json_decode(read_file("$root/src/dctcss-api.json"), true, 512, JSON_THROW_ON_ERROR);
    if (is_dir("$candidate/dist")) {
        assert_managed_entries("$candidate/dist", array_values($manifest['artifacts'] ?? []), 'Managed dist');
        foreach (directory_entries("$candidate/dist") as $entry) {
            if (!is_file("$candidate/dist/$entry")) {
                fail("Managed dist entry is not a file: $entry");
            }
        }
    } elseif (file_exists("$candidate/dist")) {
        fail('Managed dist must be a directory.');
    }
    if (is_dir("$candidate/public")) {
        $slugs = array_map(static fn(string $path): string => basename($path, '.php'), glob("$root/site/pages/*.php") ?: []);
        $pages = array_map(static fn(string $slug): string => "$slug.html", $slugs);
        assert_managed_entries("$candidate/public", array_merge($pages, ['assets', 'downloads', 'release.json', 'robots.txt', 'sitemap.xml']), 'Managed public');
        foreach (directory_entries("$candidate/public") as $entry) {
            $path = "$candidate/public/$entry";
            if ($entry === 'assets') {
                if (!is_dir($path)) fail('Managed public assets must be a directory.');
                assert_managed_entries($path, ['site.css', 'recipes.css', 'site.js', 'favicon.svg'], 'Managed public assets');
                foreach (directory_entries($path) as $asset) {
                    if (!is_file("$path/$asset")) fail("Managed public asset is not a file: $asset");
                }
            } elseif ($entry === 'downloads') {
                if (!is_dir($path)) fail('Managed public downloads must be a directory.');
                assert_managed_entries($path, [
                    $manifest['artifacts']['css'],
                    $manifest['artifacts']['cssMinified'],
                    $manifest['artifacts']['api'],
                    $manifest['artifacts']['validator'],
                ], 'Managed public downloads');
                foreach (directory_entries($path) as $download) {
                    if (!is_file("$path/$download")) fail("Managed public download is not a file: $download");
                }
            } elseif (!is_file($path)) {
                fail("Managed public entry is not a file: $entry");
            }
        }
    } elseif (file_exists("$candidate/public")) {
        fail('Managed public must be a directory.');
    }
    return $candidate;
}
