<?php

declare(strict_types=1);

function css_files(string $sourceDirectory): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDirectory, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'css') {
            $files[] = str_replace('\\', '/', substr($file->getPathname(), strlen($sourceDirectory) + 1));
        }
    }
    sort($files);
    return $files;
}

function css_declaration_names(string $block): array
{
    preg_match_all('/--dct-[a-z0-9-]+\s*:/i', $block, $matches);
    return array_values(array_unique(array_map(static fn(string $match): string => rtrim($match, " \t:"), $matches[0])));
}

function css_block_after(string $css, string $selector, string $label): string
{
    $selectorOffset = strpos($css, $selector);
    $open = $selectorOffset === false ? false : strpos($css, '{', $selectorOffset + strlen($selector));
    $close = $open === false ? false : strpos($css, '}', $open + 1);
    if ($selectorOffset === false || $open === false || $close === false) {
        fail("Cannot locate CSS context: $label.");
    }
    return substr($css, $open + 1, $close - $open - 1);
}

function validate_token_contexts(string $root, array $manifest): void
{
    $themes = preg_replace('~/\*.*?\*/~s', '', read_file("$root/src/themes/themes.css")) ?? '';
    $themeTokens = array_values(array_filter(
        $manifest['tokens'],
        static fn(string $token): bool => str_starts_with($token, '--dct-color-')
            || str_starts_with($token, '--dct-shadow-')
            || str_starts_with($token, '--dct-navbar-inverse-'),
    ));
    $contexts = [
        'light theme' => '[data-dct-theme="light"]',
        'automatic dark theme' => ':root:not([data-dct-theme="light"])',
        'explicit dark theme' => '[data-dct-theme="dark"]',
    ];
    foreach ($contexts as $label => $selector) {
        exact_set(css_declaration_names(css_block_after($themes, $selector, $label)), $themeTokens, "$label token declarations");
    }

    $tokens = preg_replace('~/\*.*?\*/~s', '', read_file("$root/src/tokens/tokens.css")) ?? '';
    $compact = css_declaration_names(css_block_after($tokens, '[data-dct-density="compact"]', 'compact density'));
    $requiredCompact = names_from_lines('--dct-control-height --dct-control-padding-block --dct-control-padding-inline --dct-control-gap --dct-panel-padding --dct-navigation-padding-block --dct-navigation-padding-inline --dct-card-padding --dct-table-cell-padding');
    $missing = array_diff($requiredCompact, $compact);
    if ($missing !== []) {
        fail('Compact density does not override required inherited tokens: ' . implode(', ', $missing));
    }
}

function validate_css(string $root, array $manifest): void
{
    $sourceDirectory = "$root/src";
    $entry = read_file("$sourceDirectory/dct.css");
    $expectedLayer = '@layer dct-reset, dct-tokens, dct-base, dct-layout, dct-components, dct-utilities, dct-themes;';
    if (substr_count($entry, $expectedLayer) !== 1) {
        fail('The cascade layer declaration is missing, duplicated or out of order.');
    }
    $includes = [];
    foreach (preg_split('/\R/', $entry) as $lineNumber => $line) {
        if ($line === '' || $line === $expectedLayer) {
            continue;
        }
        if (preg_match('~^/\* dct-include: ((?:[a-z0-9-]+/)*[a-z0-9-]+\.css) \*/$~', $line, $match) !== 1) {
            fail('Unsafe or uninterpretable CSS entry line ' . ($lineNumber + 1) . '.');
        }
        $includes[] = $match[1];
    }
    if (count($includes) !== count(array_unique($includes))) {
        fail('CSS includes must be unique.');
    }
    $allCss = css_files($sourceDirectory);
    $allCss = array_values(array_diff($allCss, ['dct.css']));
    exact_set($includes, $allCss, 'CSS includes');

    $css = '';
    foreach ($includes as $include) {
        $css .= "\n" . read_file("$sourceDirectory/$include");
    }
    $withoutComments = preg_replace('~/\*.*?\*/~s', '', $css);
    if ($withoutComments === null) {
        fail('Unable to normalize CSS comments.');
    }
    if (str_contains($withoutComments, '!important')) {
        fail('!important is not allowed in framework sources.');
    }

    $contractPatterns = [
        'error fields style descendant controls' => '/\.dct-field\.dct-is-error\s+:is\(\.dct-input,\s*\.dct-textarea,\s*\.dct-select\)\s*\{[^}]*border-color:\s*var\(--dct-color-danger\);/s',
        'numeric table cells use tabular end-aligned numbers' => '/\.dct-table\s+:is\(th,\s*td\)\.dct-is-numeric\s*\{[^}]*font-variant-numeric:\s*tabular-nums;[^}]*text-align:\s*end;/s',
        'mobile fullwidth buttons fill the base viewport' => '/\.dct-button\.dct-is-fullwidth-mobile\)\s*\{[^}]*inline-size:\s*100%;/s',
        'mobile fullwidth buttons reset at sm' => '/@media\s*\(min-width:\s*40rem\)\s*\{\s*:where\(\.dct-button\.dct-is-fullwidth-mobile\)\s*\{[^}]*inline-size:\s*auto;/s',
    ];
    foreach ($contractPatterns as $label => $pattern) {
        if (preg_match($pattern, $withoutComments) !== 1) {
            fail("CSS contract is missing: $label.");
        }
    }
    if (preg_match('/:where\(\.dct-icon\)\s*\{[^}]*(?:^|[;{]\s*)(?:fill|stroke)\s*:/sm', $withoutComments) === 1) {
        fail('.dct-icon must not impose fill or stroke.');
    }

    preg_match_all('/\.[a-zA-Z_][a-zA-Z0-9_-]*/', $withoutComments, $classMatches);
    $cssClasses = array_values(array_unique($classMatches[0]));
    foreach ($cssClasses as $className) {
        if (!str_starts_with($className, '.dct-')) {
            fail("CSS class does not use the dct- prefix: $className");
        }
    }
    $actualModifiers = array_values(array_filter($cssClasses, static fn(string $name): bool => str_starts_with($name, '.dct-is-')));
    $actualClasses = array_values(array_diff($cssClasses, $actualModifiers));
    $expectedClasses = array_values(array_diff($manifest['classNames'], $manifest['markers']));
    exact_set($actualClasses, $expectedClasses, 'CSS classes');
    exact_set($actualModifiers, $manifest['modifierNames'], 'CSS modifiers');

    preg_match_all('/--[a-zA-Z_][a-zA-Z0-9_-]*/', $withoutComments, $tokenMatches);
    $actualTokens = array_values(array_unique($tokenMatches[0]));
    foreach ($actualTokens as $token) {
        if (!str_starts_with($token, '--dct-')) {
            fail("Custom property does not use the dct- prefix: $token");
        }
    }
    exact_set($actualTokens, $manifest['tokens'], 'CSS tokens');

    preg_match_all('/@keyframes\s+([a-zA-Z_][a-zA-Z0-9_-]*)/', $withoutComments, $animationMatches);
    $actualAnimations = array_values(array_unique($animationMatches[1]));
    foreach ($actualAnimations as $animation) {
        if (!str_starts_with($animation, 'dct-')) {
            fail("Animation does not use the dct- prefix: $animation");
        }
    }
    exact_set($actualAnimations, $manifest['animations'], 'CSS animations');

    preg_match_all('/\b(data-[a-zA-Z0-9-]+)\b/', $withoutComments, $dataMatches);
    $actualDataAttributes = array_values(array_unique($dataMatches[1]));
    $expectedDataAttributes = array_values(array_filter(
        $manifest['attributeNames'],
        static fn(string $attribute): bool => str_starts_with($attribute, 'data-dct-'),
    ));
    exact_set($actualDataAttributes, $expectedDataAttributes, 'CSS data-dct attributes');
    if (version_compare($manifest['api']['version'], '1.4.0', '>=')) {
        validate_token_contexts($root, $manifest);
    }
}

function validate_local_reference(string $reference, string $baseDirectory, string $allowedDirectory, string $label): void
{
    $reference = trim($reference, " \t\n\r\0\x0B\"'");
    if ($reference === '' || preg_match('~^[a-z][a-z0-9+.-]*:~i', $reference) === 1 || str_starts_with($reference, '//') || str_starts_with($reference, '/')) {
        fail("Invalid or remote reference in $label: $reference");
    }
    $path = rawurldecode((string) parse_url($reference, PHP_URL_PATH));
    if ($path === '' || in_array('..', explode('/', str_replace('\\', '/', $path)), true)) {
        fail("Empty or escaping reference in $label: $reference");
    }
    $target = realpath($baseDirectory . DIRECTORY_SEPARATOR . $path);
    $allowed = realpath($allowedDirectory);
    if ($target === false || $allowed === false || !is_file($target) || !path_is_within($target, $allowed)) {
        fail("Missing or escaping local reference in $label: $reference");
    }
}

function validate_css_resources(string $css, string $path, string $baseDirectory, string $allowedDirectory): void
{
    $withoutComments = preg_replace('~/\*.*?\*/~s', '', $css);
    if ($withoutComments === null) {
        fail("Cannot normalize CSS resources in $path.");
    }
    preg_match_all('~url\(\s*(["\']?)(.*?)\1\s*\)~i', $withoutComments, $urls, PREG_SET_ORDER);
    foreach ($urls as $url) {
        validate_local_reference($url[2], $baseDirectory, $allowedDirectory, $path);
    }
    preg_match_all('~@import\s+([^;]+);~i', $withoutComments, $imports, PREG_SET_ORDER);
    foreach ($imports as $import) {
        if (preg_match('~^\s*url\(\s*(["\']?)(.*?)\1\s*\)(?:\s+.*)?$~i', $import[1], $match) === 1
            || preg_match('~^\s*(["\'])(.*?)\1(?:\s+.*)?$~', $import[1], $match) === 1) {
            validate_local_reference($match[2], $baseDirectory, $allowedDirectory, $path);
            continue;
        }
        fail("Unsupported CSS @import in $path.");
    }
}

function validate_benchmarks(string $path): void
{
    $contents = read_file($path);
    if (!str_contains($contents, "curl -fL --proto '=https' --tlsv1.2") || !str_contains($contents, 'wc -c FILE') || !str_contains($contents, 'sha256sum FILE')) {
        fail('Benchmarks must document the approved acquisition and verification procedure.');
    }
    $rows = [];
    foreach (preg_split('/\R/', $contents) as $lineNumber => $line) {
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $fields = explode('|', $line);
        if (count($fields) !== 5 || in_array('', $fields, true)) {
            fail('Invalid benchmark row at line ' . ($lineNumber + 1) . '.');
        }
        [$name, $version, $size, $gzipSize, $reference] = $fields;
        if (isset($rows[$name])) {
            fail("Duplicate benchmark: $name");
        }
        $isApproximate = str_starts_with($size, '~');
        if ($isApproximate && preg_match('/^(?:\d+\.\d+(?:\.\d+)?|\d+\.x)$/', $version) !== 1) {
            fail("Invalid approximate benchmark version for $name.");
        }
        if (!$isApproximate && preg_match('/^\d+\.\d+\.\d+$/', $version) !== 1 && !($name === 'Tailwind CSS' && $version === '4.x')) {
            fail("Invalid benchmark version for $name.");
        }
        if ($name === 'Tailwind CSS') {
            if ($size !== 'variable' || $gzipSize !== 'variable' || preg_match('~^https?://~i', $reference) === 1) {
                fail('Tailwind CSS must use a variable size and explanatory reference.');
            }
        } elseif ($isApproximate) {
            if (preg_match('/^~[0-9]+(?:-[0-9]+)? KB$/', $size) !== 1
                || preg_match('/^~[0-9]+(?:-[0-9]+)? KB$/', $gzipSize) !== 1
                || !str_starts_with($reference, 'comparison-image-2026-07-28:')) {
                fail("Approximate benchmark $name requires ranges and recorded provenance.");
            }
        } elseif (preg_match('/^[1-9][0-9]*$/', $size) !== 1
            || preg_match('/^[1-9][0-9]*$/', $gzipSize) !== 1
            || preg_match('~^https://[^|[:space:]]+$~', $reference) !== 1) {
            fail("Fixed benchmark $name requires a positive byte count and HTTPS reference.");
        } elseif (preg_match('/^# sha256 ' . preg_quote($name, '/') . ' ' . preg_quote($version, '/') . ': [a-f0-9]{64}$/m', $contents) !== 1) {
            fail("Fixed benchmark $name lacks its SHA-256 provenance.");
        }
        $rows[$name] = [$version, $size, $gzipSize, $reference];
    }
    exact_set(array_keys($rows), ['Pure.css', 'Pico', 'Spectre.css', 'Bootstrap', 'Foundation', 'Bulma', 'Materialize', 'UIkit', 'Tailwind CSS'], 'Benchmark frameworks');
    $references = array_column($rows, 3);
    if (count($references) !== count(array_unique($references))) {
        fail('Benchmark references must be unique.');
    }
}
