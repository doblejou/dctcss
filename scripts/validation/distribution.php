<?php

declare(strict_types=1);

function validate_generated_permissions(string $directory, string $label): void
{
    if ((fileperms($directory) & 0777) !== 0755) {
        fail("$label directory permissions must be 0755.");
    }
    foreach (directory_entries($directory) as $entry) {
        $path = "$directory/$entry";
        if (is_dir($path)) {
            validate_generated_permissions($path, "$label/$entry");
        } elseif (!is_file($path) || (fileperms($path) & 0777) !== 0644) {
            fail("$label/$entry must be a regular file with 0644 permissions.");
        }
    }
}

function validate_skill_package(string $root): void
{
    $directory = "$root/skills/dctcss";
    exact_set(directory_entries($directory), ['SKILL.md', 'LICENSE', 'references'], 'Skill package entries');
    if (read_file("$directory/LICENSE") !== read_file("$root/LICENSE")) {
        fail('The skill license differs from the project license.');
    }
}

function validate_version_authorities(string $root, string $version): void
{
    $majorMinor = implode('.', array_slice(explode('.', $version), 0, 2));
    $requirements = [
        'README.md' => "`$version`",
        'CHANGELOG.md' => "## $version -",
        'COMPATIBILITY.md' => "`$version` es la versión actual",
        'docs/RECIPES.md' => "# Recetas dctcss $version",
        'skills/dctcss/SKILL.md' => "# dctcss $version",
    ];
    foreach ($requirements as $relative => $needle) {
        if (!str_contains(read_file("$root/$relative"), $needle)) {
            fail("$relative does not identify release $version.");
        }
    }
    if (!str_contains(read_file("$root/COMPATIBILITY.md"), "dctcss $majorMinor requiere")) {
        fail('COMPATIBILITY.md does not match the release series.');
    }
}

function current_release_date(string $changelog, string $version): string
{
    $pattern = '/^## ' . preg_quote($version, '/') . ' - (\d{4}-\d{2}-\d{2})$/m';
    if (preg_match_all($pattern, $changelog, $matches) !== 1) {
        fail("CHANGELOG.md must contain exactly one release entry for $version.");
    }
    [$year, $month, $day] = array_map('intval', explode('-', $matches[1][0]));
    if (!checkdate($month, $day, $year)) {
        fail("CHANGELOG.md contains an invalid release date for $version.");
    }
    return $matches[1][0];
}

function validate_distribution(string $root, string $distDirectory, string $version, array $manifest): void
{
    exact_set(directory_entries($distDirectory), array_values($manifest['artifacts']), 'Distribution artifacts');
    $apiName = $manifest['artifacts']['api'];
    $recipeName = $manifest['artifacts']['recipes'];
    $changelogName = $manifest['artifacts']['changelog'];
    $licenseName = $manifest['artifacts']['license'];
    $cssName = $manifest['artifacts']['css'];
    $minifiedName = $manifest['artifacts']['cssMinified'];
    $checksumName = $manifest['artifacts']['checksums'];
    $validatorName = $manifest['artifacts']['validator'];
    if (read_file("$distDirectory/$apiName") !== read_file("$root/src/dctcss-api.json")) {
        fail('The staged API manifest differs from its source.');
    }
    if (read_file("$distDirectory/$recipeName") !== read_file("$root/docs/RECIPES.md")) {
        fail('The staged recipes differ from their source.');
    }
    if (read_file("$distDirectory/$changelogName") !== read_file("$root/CHANGELOG.md")) {
        fail('The staged changelog differs from its source.');
    }
    if (read_file("$distDirectory/$licenseName") !== read_file("$root/LICENSE")) {
        fail('The staged license differs from its source.');
    }
    if (read_file("$distDirectory/$validatorName") !== read_file("$root/scripts/dctcss-validate.php")) {
        fail('The staged contract validator differs from its source.');
    }
    $css = read_file("$distDirectory/$cssName");
    $minified = read_file("$distDirectory/$minifiedName");
    if (!str_starts_with($css, "/*! dctcss v$version | MIT License | Generated from src/; do not edit. */\n")) {
        fail('The readable CSS banner is invalid.');
    }
    if (!str_starts_with($minified, "/*! dctcss v$version | MIT License | Minified build; do not edit. */\n") || strlen($minified) >= strlen($css)) {
        fail('The minified CSS banner or size is invalid.');
    }
    if (substr_count($minified, ':where(*),:where(*):before,:where(*):after{box-sizing:border-box}') !== 1) {
        fail('The minified CSS does not preserve the pseudo-element box-sizing reset.');
    }
    $checksumNames = [$cssName, $minifiedName, $apiName, $recipeName, $changelogName, $licenseName, $validatorName];
    $expectedChecksums = '';
    foreach ($checksumNames as $name) {
        $expectedChecksums .= hash_file('sha256', "$distDirectory/$name") . "  $name\n";
    }
    if (read_file("$distDirectory/$checksumName") !== $expectedChecksums) {
        fail('The staged checksum manifest is invalid.');
    }
}

function validate_source_css_resources(string $root): void
{
    foreach (css_files("$root/src") as $relative) {
        $path = "$root/src/$relative";
        validate_css_resources(read_file($path), $path, dirname($path), "$root/src");
    }
    validate_css_resources(read_file("$root/site/site.css"), "$root/site/site.css", "$root/site", "$root/site");
}
