<?php

declare(strict_types=1);

function markdown_section(string $contents, string $heading, string $label): string
{
    $pattern = '/^## ' . preg_quote($heading, '/') . '[ \t]*\R(.*?)(?=^## |\z)/ms';
    $count = preg_match_all($pattern, $contents, $matches);
    if ($count !== 1 || trim($matches[1][0]) === '') {
        fail("Skill must contain exactly one non-empty $label section.");
    }
    return $matches[1][0];
}

function validate_references(string $root, string $directory, string $version): void
{
    exact_set(directory_entries($directory), ['dctcss-api.json', 'RECIPES.md', 'CHANGELOG.md'], 'Skill references');
    foreach (['dctcss-api.json', 'RECIPES.md', 'CHANGELOG.md'] as $name) {
        if (!is_file("$directory/$name") || is_link("$directory/$name")) {
            fail("Skill reference must be a regular file: $name");
        }
    }
    if (read_file("$directory/dctcss-api.json") !== read_file("$root/src/dctcss-api.json")) {
        fail('The skill API reference differs from its source.');
    }
    if (read_file("$directory/RECIPES.md") !== read_file("$root/docs/RECIPES.md")) {
        fail('The skill recipes reference differs from its source.');
    }
    if (read_file("$directory/CHANGELOG.md") !== read_file("$root/CHANGELOG.md")) {
        fail('The skill changelog reference differs from its source.');
    }
    try {
        $api = json_decode(read_file("$directory/dctcss-api.json"), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        fail("Invalid skill API reference JSON: {$error->getMessage()}");
    }
    if (!is_array($api) || ($api['version'] ?? null) !== $version) {
        fail('The skill API reference version is stale.');
    }
}

function validate_skill(string $path, string $referencesDirectory, array $manifest, string $version): void
{
    $skill = read_file($path);
    if (!str_contains($skill, "dctcss $version")) {
        fail('The skill version is stale.');
    }
    $workflow = markdown_section($skill, 'Flujo operativo', 'workflow');
    $matrix = markdown_section($skill, 'Matriz de decisión', 'decision matrix');
    $checklist = markdown_section($skill, 'Checklist obligatorio de salida', 'output checklist');
    if (preg_match_all('/^\d+\. /m', $workflow) < 6) {
        fail('The skill workflow is incomplete.');
    }
    if (!str_contains($matrix, '| Necesidad | Elección |') || preg_match_all('/^\|[^\r\n]+\|$/m', $matrix) < 5) {
        fail('The skill decision matrix is incomplete.');
    }
    if (preg_match_all('/^- /m', $checklist) < 8) {
        fail('The skill output checklist is incomplete.');
    }

    preg_match_all('~(?<![A-Za-z0-9_./-])references/[A-Za-z0-9._/-]+~', $skill, $referenceMatches);
    $localReferences = array_values(array_unique($referenceMatches[0]));
    exact_set($localReferences, ['references/dctcss-api.json', 'references/RECIPES.md', 'references/CHANGELOG.md'], 'Skill local references');
    foreach ($localReferences as $reference) {
        validate_local_reference(substr($reference, strlen('references/')), $referencesDirectory, $referencesDirectory, 'Skill');
    }
    preg_match_all('~!?\[[^\]]*\]\(\s*<?([^\s)>]+)|(?:href|src)\s*=\s*["\']([^"\']+)["\']~i', $skill, $activeLinks, PREG_SET_ORDER);
    foreach ($activeLinks as $link) {
        $target = $link[1] !== '' ? $link[1] : ($link[2] ?? '');
        $targetPath = str_replace('\\', '/', rawurldecode((string) parse_url($target, PHP_URL_PATH)));
        if (preg_match('~(?:^|/)dist/(?:dctcss-api\.json|RECIPES\.md|CHANGELOG\.md)$~i', $targetPath) === 1) {
            fail("Skill contains an active dist reference link: $target");
        }
    }
    foreach (['classNames', 'modifierNames', 'tokens', 'animations', 'attributeNames'] as $section) {
        foreach ($manifest[$section] as $name) {
            if (!str_contains($skill, $name)) {
                fail("Skill does not cover $name.");
            }
        }
    }
    foreach ($manifest['unavailable'] as $name) {
        if (str_starts_with($name, '.') && !str_contains($skill, $name)) {
            fail("Skill does not document unavailable API: $name");
        }
    }
    preg_match_all('/^```html\R(.*?)^```$/ms', $skill, $htmlFences);
    foreach ($htmlFences[1] as $index => $html) {
        validate_copyable_snippet($html, $manifest, 'Skill HTML block ' . ($index + 1));
    }
    validate_documented_api($skill, $manifest, 'Skill');
}

function validate_recipe_contracts(string $contents, array $manifest, string $version): void
{
    $requirements = [
        'compact list heading' => '## Lista compacta de pares',
        'compact list semantic root' => '<dl class="app-compact-list">',
        'compact list terms' => '<dt>Categoría</dt>',
        'compact list descriptions' => '<dd>Servicios</dd>',
        'compact list separators' => 'border-block-end: 1px solid var(--dct-color-border);',
        'compact list application namespace' => '.app-compact-list__row',
    ];
    foreach ($requirements as $label => $needle) {
        if (!str_contains($contents, $needle)) {
            fail("Recipes contract is missing: $label.");
        }
    }
    preg_match_all('/^```html\R(.*?)^```$/ms', $contents, $htmlFences);
    foreach ($htmlFences[1] as $index => $html) {
        validate_copyable_snippet($html, $manifest, 'Recipes HTML block ' . ($index + 1));
    }
    if (version_compare($version, '1.4.0', '<')) {
        return;
    }
    $releaseRequirements = [
        'page header recipe' => '## Encabezado de página',
        'dashboard recipe' => '## Dashboard básico',
        'empty state recipe' => '## Empty state',
        'activity recipe' => '## Lista de actividad',
        'icon button recipe' => '## Botón de icono accesible',
        'compact density usage' => 'data-dct-density="compact"',
        'accessible icon button' => 'dct-is-icon-only',
        'application recipe namespace' => 'class="app-',
    ];
    foreach ($releaseRequirements as $label => $needle) {
        if (!str_contains($contents, $needle)) {
            fail("Release 1.4 recipes contract is missing: $label.");
        }
    }
    if (preg_match('/^## .*ajustes.*compacta$/imu', $contents) !== 1) {
        fail('Release 1.4 recipes contract is missing: compact settings recipe.');
    }
    if (!str_contains($contents, 'Las clases `app-*`') || !str_contains($contents, 'pertenecen a la aplicación')) {
        fail('Release 1.4 recipes must distinguish application classes from stable dctcss API.');
    }
    if (version_compare($version, '1.5.0', '<')) {
        return;
    }
    $release15Requirements = [
        'administrative shell recipe' => '## Shell administrativo responsive',
        'inverse shell navbar' => 'dct-navbar dct-is-inverse',
        'filter panel recipe' => '## Panel de filtros responsive',
        'mobile sticky form recipe' => '## Formulario móvil con acciones sticky',
        'sticky application namespace' => '.app-sticky-actions',
        'elevated table recipe' => 'dct-table-wrap dct-is-elevated',
    ];
    foreach ($release15Requirements as $label => $needle) {
        if (!str_contains($contents, $needle)) {
            fail("Release 1.5 recipes contract is missing: $label.");
        }
    }
}
