#!/usr/bin/env php
<?php

declare(strict_types=1);

foreach (['common', 'config', 'manifest', 'css', 'html', 'site', 'skill', 'distribution'] as $module) {
    require_once __DIR__ . "/validation/$module.php";
}

try {
    $root = dirname(__DIR__);
    if ($argc === 2 && $argv[1] === '--print-site-url') {
        fwrite(STDOUT, load_site_url($root) . "\n");
        exit(0);
    }
    if ($argc === 2 && $argv[1] === '--assert-production') {
        $config = load_site_config($root);
        if ($config['environment'] !== 'production') {
            fail('DCTCSS_ENV must be production for a release check.');
        }
        fwrite(STDOUT, "Production configuration passed.\n");
        exit(0);
    }
    if ($argc === 3 && in_array($argv[1], ['--resolve-output-root', '--canonical-output-root'], true)) {
        fwrite(STDOUT, canonical_output_root($root, $argv[2], $argv[1] === '--canonical-output-root') . "\n");
        exit(0);
    }
    if (!in_array($argc, [1, 4, 5, 6], true)) {
        fail('Usage: php scripts/validate.php [PUBLIC_DIR DIST_DIR SITE_URL [REFERENCES_DIR [SKILL_FILE]]]');
    }
    $publicDirectory = $argc >= 4 ? rtrim($argv[1], DIRECTORY_SEPARATOR) : "$root/public";
    $distDirectory = $argc >= 4 ? rtrim($argv[2], DIRECTORY_SEPARATOR) : "$root/dist";
    $siteUrl = $argc >= 4 ? validate_site_url($argv[3]) : load_site_url($root);
    $referencesDirectory = $argc >= 5 ? rtrim($argv[4], DIRECTORY_SEPARATOR) : "$root/skills/dctcss/references";
    $skillPath = $argc === 6 ? $argv[5] : "$root/skills/dctcss/SKILL.md";
    $versionSource = read_file("$root/VERSION");
    if (preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\n?$/D', $versionSource) !== 1) {
        fail('VERSION must contain only one semantic version.');
    }
    $version = trim($versionSource);
    validate_version_authorities($root, $version);
    $manifest = validate_manifest("$root/src/dctcss-api.json", $version);
    validate_release_api($manifest, $version);
    validate_css($root, $manifest);
    validate_benchmarks("$root/benchmarks/framework-sizes.txt");
    validate_skill_package($root);
    validate_references($root, $referencesDirectory, $version);
    validate_skill($skillPath, $referencesDirectory, $manifest, $version);
    $recipes = read_file("$root/docs/RECIPES.md");
    validate_recipe_contracts($recipes, $manifest, $version);
    validate_documented_api($recipes, $manifest, 'Recipes');
    validate_source_css_resources($root);
    validate_public($root, $publicDirectory, $distDirectory, $siteUrl, $manifest, $version);
    validate_distribution($root, $distDirectory, $version, $manifest);
    validate_generated_permissions($publicDirectory, 'public');
    validate_generated_permissions($distDirectory, 'dist');
    validate_generated_permissions($referencesDirectory, 'skill references');
    if ($argc === 1 && is_dir("$root/demo")) {
        fail('The obsolete demo directory still exists.');
    }
    fwrite(STDOUT, "Validation passed.\n");
} catch (ValidationFailure $error) {
    fwrite(STDERR, "validate: {$error->getMessage()}\n");
    exit(1);
} catch (Throwable $error) {
    fwrite(STDERR, "validate: {$error->getMessage()}\n");
    exit(1);
}
