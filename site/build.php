#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/template.php';
require_once __DIR__ . '/../scripts/validation/common.php';
require_once __DIR__ . '/../scripts/validation/config.php';
require_once __DIR__ . '/../scripts/validation/distribution.php';

if ($argc !== 4) {
    fwrite(STDERR, "Usage: php site/build.php OUTPUT_PUBLIC_DIR SITE_URL STAGED_DIST_DIR\n");
    exit(2);
}

$outputDirectory = rtrim($argv[1], DIRECTORY_SEPARATOR);
$siteUrl = $argv[2];
$stagedDistDirectory = rtrim($argv[3], DIRECTORY_SEPARATOR);
$projectRoot = dirname(__DIR__);

function read_required_file(string $path, string $label): string
{
    $contents = is_file($path) ? file_get_contents($path) : false;
    if ($contents === false) {
        throw new RuntimeException("Cannot read $label: $path");
    }
    return $contents;
}

function artifact_metadata(string $directory, string $filename): array
{
    if ($filename !== basename($filename) || !str_ends_with($filename, '.css')) {
        throw new RuntimeException("Invalid public CSS artifact name: $filename");
    }

    $path = $directory . DIRECTORY_SEPARATOR . $filename;
    if (!is_file($path)) {
        throw new RuntimeException("Missing staged CSS artifact: $path");
    }

    $contents = read_file($path);
    $gzip = gzencode($contents, 9, ZLIB_ENCODING_GZIP);
    if ($gzip === false) {
        throw new RuntimeException("Cannot inspect staged CSS artifact: $path");
    }

    return ['filename' => $filename, 'bytes' => strlen($contents), 'gzipBytes' => strlen($gzip)];
}

function read_benchmarks(string $path, string $version, array $minified): array
{
    $rows = [];
    $rows[] = ['name' => 'dctcss', 'version' => $version, 'bytes' => $minified['bytes'],
        'gzipBytes' => $minified['gzipBytes'], 'measurement' => 'Build actual', 'sortBytes' => $minified['bytes']];
    $snapshot = read_required_file($path, 'benchmark file');
    if (!preg_match('/^# measured-at: (\d{4}-\d{2}-\d{2})$/m', $snapshot, $date))
    {
        throw new RuntimeException('Missing benchmark measurement date.');
    }

    foreach (preg_split('/\R/', $snapshot) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $fields = explode('|', $line, 5);
        if (count($fields) !== 5 || in_array('', $fields, true)) {
            throw new RuntimeException("Invalid benchmark row: $line");
        }
        [$name, $release, $bytes, $gzipBytes] = $fields;
        $measurement = 'Referencia fijada';
        $sortBytes = (int) $bytes;
        if ($bytes === 'variable')
        {
            $measurement = 'Depende del proyecto';
            $sortBytes = PHP_INT_MAX;
        }

        $rows[] = ['name' => $name, 'version' => $release, 'bytes' => $bytes,
            'gzipBytes' => $gzipBytes, 'measurement' => $measurement, 'sortBytes' => $sortBytes];
    }

    usort($rows, static fn(array $a, array $b): int => $a['sortBytes'] <=> $b['sortBytes']);
    return ['date' => $date[1], 'rows' => $rows];
}

function read_browser_minimums(string $path): array
{
    $compatibility = read_required_file($path, 'COMPATIBILITY.md');
    $minimums = [];
    foreach (['Chrome y Chromium', 'Firefox', 'Safari'] as $browser) {
        $pattern = '/^\| ' . preg_quote($browser, '/') . ' \| ([^|]+) \|$/m';
        if (preg_match($pattern, $compatibility, $match) !== 1) {
            throw new RuntimeException("Missing browser minimum in COMPATIBILITY.md: $browser");
        }
        $minimums[$browser] = trim($match[1]);
    }
    return $minimums;
}

function write_output(string $path, string $contents): void
{
    if (file_put_contents($path, $contents) === false) {
        throw new RuntimeException("Cannot write public output: $path");
    }
    chmod($path, 0644);
}

$environment = load_site_config($projectRoot)['environment'];
validate_site_url($siteUrl, $environment);

$version = trim(read_required_file($projectRoot . '/VERSION', 'VERSION'));
if (!preg_match('/^\d+\.\d+\.\d+$/', $version)) {
    throw new RuntimeException('VERSION must contain a semantic version.');
}

$manifest = json_decode(read_required_file($projectRoot . '/src/dctcss-api.json', 'API manifest'), true, flags: JSON_THROW_ON_ERROR);
if (!is_array($manifest) || ($manifest['version'] ?? null) !== $version || !is_array($manifest['artifacts'] ?? null)) {
    throw new RuntimeException('The root API manifest must match VERSION and define artifacts.');
}

$css = artifact_metadata($stagedDistDirectory, (string) ($manifest['artifacts']['css'] ?? ''));
$cssMinified = artifact_metadata($stagedDistDirectory, (string) ($manifest['artifacts']['cssMinified'] ?? ''));
$changelog = read_required_file($projectRoot . '/CHANGELOG.md', 'CHANGELOG.md');
$frameworkCssTime = filemtime($outputDirectory . '/downloads/' . $css['filename']);
$siteCssTime = filemtime($outputDirectory . '/assets/site.css');
if ($frameworkCssTime === false || $siteCssTime === false)
{
    throw new RuntimeException('Cannot read stylesheet modification times.');
}

$build = [
    'version' => $version,
    'siteUrl' => $siteUrl,
    'production' => $environment === 'production',
    'css' => $css,
    'frameworkCssTime' => $frameworkCssTime,
    'siteCssTime' => $siteCssTime,
    'cssMinified' => $cssMinified,
    'changelog' => $changelog,
    'releaseDate' => current_release_date($changelog, $version),
    'benchmarks' => read_benchmarks($projectRoot . '/benchmarks/framework-sizes.txt', $version, $cssMinified),
    'browserMinimums' => read_browser_minimums($projectRoot . '/COMPATIBILITY.md'),
];

foreach (array_keys(SITE_PAGES) as $slug) {
    $source = __DIR__ . '/pages/' . $slug . '.php';
    if (!is_file($source)) {
        throw new RuntimeException("Missing site page source: $source");
    }
    $page = require $source;
    if (!is_array($page) || ($page['slug'] ?? null) !== $slug || !is_callable($page['content'] ?? null)) {
        throw new RuntimeException("Invalid site page source: $source");
    }
    write_output($outputDirectory . '/' . $slug . '.html', render_page($page, $build));
}

$sitemapUrl = $siteUrl . '/sitemap.xml';
$recipeStyles = [];
foreach (['Dashboard básico con KPIs', 'Lista de actividad', 'Resumen por contenedor'] as $heading)
{
    $recipeStyles[] = recipe_code_block($heading, 'css');
}

write_output($outputDirectory . '/assets/recipes.css', implode("\n\n", $recipeStyles) . "\n");

write_output($outputDirectory . '/robots.txt', "User-agent: *\nAllow: /\nSitemap: $sitemapUrl\n");

$locations = [];
foreach (array_keys(SITE_PAGES) as $slug) {
    $locations[] = canonical_url($siteUrl, $slug);
}
$xmlLocations = implode("\n", array_map(
    static fn(string $url): string => '  <url><loc>' . escape($url) . '</loc></url>',
    $locations,
));
write_output(
    $outputDirectory . '/sitemap.xml',
    "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
    . "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n$xmlLocations\n</urlset>\n",
);

$release = [
    'schemaVersion' => 1,
    'name' => 'dctcss',
    'version' => $version,
    'releaseDate' => $build['releaseDate'],
    'changelog' => $siteUrl . '/changelog.html',
    'artifacts' => [
        'css' => $siteUrl . '/downloads/' . $css['filename'],
        'cssMinified' => $siteUrl . '/downloads/' . $cssMinified['filename'],
        'api' => $siteUrl . '/downloads/' . $manifest['artifacts']['api'],
        'validator' => GITHUB_REPOSITORY . '/releases/download/v' . $version . '/' . $manifest['artifacts']['validator'],
    ],
];
write_output(
    $outputDirectory . '/release.json',
    json_encode($release, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
);
