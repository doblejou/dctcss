<?php

declare(strict_types=1);

function validate_manifest(string $path, string $version): array
{
    try {
        $api = json_decode(read_file($path), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $error) {
        fail("Invalid API JSON: {$error->getMessage()}");
    }
    if (!is_array($api)) {
        fail('The API manifest root must be an object.');
    }
    exact_keys(
        $api,
        ['schemaVersion', 'name', 'version', 'prefix', 'stability', 'attributeInventoryScope', 'applicationResponsibilities', 'artifacts', 'classes', 'modifiers', 'attributes', 'tokens', 'animations', 'decisionRules', 'unavailable'],
        [],
        'API manifest',
    );
    if ($api['schemaVersion'] !== 2 || $api['name'] !== 'dctcss' || $api['version'] !== $version || $api['prefix'] !== 'dct-' || $api['stability'] !== 'stable') {
        fail('API identity, schema, version, prefix or stability is invalid.');
    }
    if (!is_string($api['attributeInventoryScope']) || $api['attributeInventoryScope'] === '') {
        fail('attributeInventoryScope must be a non-empty string.');
    }
    string_list($api['applicationResponsibilities'], 'applicationResponsibilities');
    string_list($api['decisionRules'], 'decisionRules');
    if ($api['artifacts'] !== [
        'css' => 'dct.css',
        'cssMinified' => 'dct.min.css',
        'api' => 'dctcss-api.json',
        'recipes' => 'RECIPES.md',
        'changelog' => 'CHANGELOG.md',
        'license' => 'LICENSE',
        'checksums' => 'SHA256SUMS',
        'validator' => 'dctcss-validate.php',
    ]) {
        fail('The artifact map does not match the fixed distribution names.');
    }

    if (!is_array($api['classes']) || !array_is_list($api['classes']) || $api['classes'] === []) {
        fail('classes must be a non-empty array.');
    }
    $classNames = [];
    $markers = [];
    $classArrayFields = ['elements', 'requiresAncestor', 'requiresClasses', 'requiresChild', 'requiresAttributes', 'requiresOneOfAttributes', 'requiresOneOfChildren', 'allowedTypes'];
    foreach ($api['classes'] as $index => $item) {
        if (!is_array($item)) {
            fail("classes[$index] must be an object.");
        }
        exact_keys($item, ['name', 'kind'], array_merge($classArrayFields, ['minWidth', 'marker']), "classes[$index]");
        if (!is_string($item['name']) || preg_match('/^\.dct-[a-z0-9-]+$/', $item['name']) !== 1 || !is_string($item['kind']) || $item['kind'] === '') {
            fail("classes[$index] has an invalid name or kind.");
        }
        $classNames[] = $item['name'];
        foreach ($classArrayFields as $field) {
            if (array_key_exists($field, $item)) {
                string_list($item[$field], "classes[$index].$field");
            }
        }
        if (isset($item['minWidth']) && (!is_string($item['minWidth']) || preg_match('/^\d+(?:\.\d+)?rem$/', $item['minWidth']) !== 1)) {
            fail("classes[$index].minWidth is invalid.");
        }
        if (array_key_exists('marker', $item)) {
            if ($item['marker'] !== true) {
                fail("classes[$index].marker may only be true.");
            }
            $markers[] = $item['name'];
        }
    }
    if (count($classNames) !== count(array_unique($classNames))) {
        fail('The API manifest contains duplicate classes.');
    }

    if (!is_array($api['modifiers']) || !array_is_list($api['modifiers']) || $api['modifiers'] === []) {
        fail('modifiers must be a non-empty array.');
    }
    $modifierNames = [];
    foreach ($api['modifiers'] as $index => $item) {
        if (!is_array($item)) {
            fail("modifiers[$index] must be an object.");
        }
        exact_keys($item, ['name', 'appliesTo'], ['requiresAttributes', 'requiresOneOfAttributes', 'requiresDescendantAttributes', 'requiresAttributesByElement'], "modifiers[$index]");
        if (!is_string($item['name']) || preg_match('/^\.dct-is-[a-z0-9-]+$/', $item['name']) !== 1) {
            fail("modifiers[$index].name is invalid.");
        }
        $modifierNames[] = $item['name'];
        string_list($item['appliesTo'], "modifiers[$index].appliesTo");
        foreach (['requiresAttributes', 'requiresOneOfAttributes', 'requiresDescendantAttributes'] as $field) {
            if (array_key_exists($field, $item)) {
                string_list($item[$field], "modifiers[$index].$field");
            }
        }
        if (isset($item['requiresAttributesByElement'])) {
            if (!is_array($item['requiresAttributesByElement']) || array_is_list($item['requiresAttributesByElement']) || $item['requiresAttributesByElement'] === []) {
                fail("modifiers[$index].requiresAttributesByElement must be a non-empty object.");
            }
            foreach ($item['requiresAttributesByElement'] as $element => $requirements) {
                if (!is_string($element) || preg_match('/^[a-z][a-z0-9-]*$/', $element) !== 1) {
                    fail("modifiers[$index].requiresAttributesByElement has an invalid element.");
                }
                string_list($requirements, "modifiers[$index].requiresAttributesByElement.$element");
            }
        }
    }
    if (count($modifierNames) !== count(array_unique($modifierNames))) {
        fail('The API manifest contains duplicate modifiers.');
    }
    if (array_intersect($classNames, $modifierNames) !== []) {
        fail('Classes and modifiers overlap.');
    }

    if (!is_array($api['attributes']) || !array_is_list($api['attributes']) || $api['attributes'] === []) {
        fail('attributes must be a non-empty array.');
    }
    $attributeNames = [];
    foreach ($api['attributes'] as $index => $item) {
        if (!is_array($item)) {
            fail("attributes[$index] must be an object.");
        }
        exact_keys($item, ['name', 'use'], ['value', 'values'], "attributes[$index]");
        if (!is_string($item['name']) || preg_match('/^(?:data-dct-[a-z0-9-]+|[a-z][a-z0-9-]*)$/', $item['name']) !== 1 || !is_string($item['use']) || $item['use'] === '') {
            fail("attributes[$index] has an invalid name or use.");
        }
        if (array_key_exists('value', $item) === array_key_exists('values', $item)) {
            if (array_key_exists('value', $item)) {
                fail("attributes[$index] must not define both value and values.");
            }
        }
        if (isset($item['value']) && (!is_string($item['value']) || $item['value'] === '')) {
            fail("attributes[$index].value is invalid.");
        }
        if (isset($item['values'])) {
            string_list($item['values'], "attributes[$index].values");
        }
        $attributeNames[] = $item['name'];
    }
    if (count($attributeNames) !== count(array_unique($attributeNames))) {
        fail('The API manifest contains duplicate attributes.');
    }
    $knownAttributes = array_fill_keys($attributeNames, true);
    foreach ($api['classes'] as $index => $item) {
        foreach (['requiresAttributes', 'requiresOneOfAttributes'] as $field) {
            foreach ($item[$field] ?? [] as $requirement) {
                $attribute = explode('=', $requirement, 2)[0];
                if (!isset($knownAttributes[$attribute])) {
                    fail("classes[$index].$field references an uninventoried attribute: $attribute");
                }
            }
        }
    }
    foreach ($api['modifiers'] as $index => $item) {
        foreach (['requiresAttributes', 'requiresOneOfAttributes', 'requiresDescendantAttributes'] as $field) {
            foreach ($item[$field] ?? [] as $requirement) {
                $attribute = explode('=', $requirement, 2)[0];
                if (!isset($knownAttributes[$attribute])) {
                    fail("modifiers[$index].$field references an uninventoried attribute: $attribute");
                }
            }
        }
        foreach ($item['requiresAttributesByElement'] ?? [] as $element => $requirements) {
            foreach ($requirements as $requirement) {
                $attribute = explode('=', $requirement, 2)[0];
                if (!isset($knownAttributes[$attribute])) {
                    fail("modifiers[$index].requiresAttributesByElement.$element references an uninventoried attribute: $attribute");
                }
            }
        }
    }

    $tokens = string_list($api['tokens'], 'tokens');
    foreach ($tokens as $token) {
        if (preg_match('/^--dct-[a-z0-9-]+$/', $token) !== 1) {
            fail("Invalid token name: $token");
        }
    }
    $animations = string_list($api['animations'], 'animations');
    foreach ($animations as $animation) {
        if (preg_match('/^dct-[a-z0-9-]+$/', $animation) !== 1) {
            fail("Invalid animation name: $animation");
        }
    }
    $unavailable = string_list($api['unavailable'], 'unavailable');
    if (array_intersect($unavailable, array_merge($classNames, $modifierNames, $tokens, $animations)) !== []) {
        fail('Available and unavailable API names overlap.');
    }

    $knownClasses = array_fill_keys($classNames, true);
    foreach (array_merge($api['classes'], $api['modifiers']) as $item) {
        foreach ($item as $value) {
            $values = is_array($value) ? $value : [$value];
            foreach ($values as $candidate) {
                if (!is_string($candidate)) {
                    continue;
                }
                foreach (css_references($candidate) as $reference) {
                    if (!isset($knownClasses[$reference]) && !in_array($reference, $modifierNames, true)) {
                        fail("Manifest cross-reference is not inventoried: $reference");
                    }
                }
            }
        }
    }

    $artifacts = $api['artifacts'];
    $classesByName = array_column($api['classes'], null, 'name');
    $modifiersByName = array_column($api['modifiers'], null, 'name');
    $attributesByName = array_column($api['attributes'], null, 'name');
    return compact('api', 'artifacts', 'classNames', 'modifierNames', 'attributeNames', 'tokens', 'animations', 'markers', 'unavailable', 'classesByName', 'modifiersByName', 'attributesByName');
}

function names_from_lines(string $names): array
{
    return preg_split('/\s+/', trim($names), -1, PREG_SPLIT_NO_EMPTY) ?: [];
}

function canonicalize_contract(mixed &$value): void
{
    if (!is_array($value)) {
        return;
    }
    foreach ($value as &$item) {
        canonicalize_contract($item);
    }
    unset($item);
    if (!array_is_list($value)) {
        ksort($value);
    } elseif ($value !== [] && is_array($value[0]) && isset($value[0]['name'])) {
        usort($value, static fn(array $left, array $right): int => $left['name'] <=> $right['name']);
    } elseif (count(array_filter($value, 'is_string')) === count($value)) {
        sort($value);
    }
}

function validate_release_api(array $manifest, string $version): void
{
    if (version_compare($version, '1.4.0', '<')) {
        return;
    }

    $classes12 = names_from_lines(<<<'NAMES'
.dct-container .dct-stack .dct-cluster .dct-grid .dct-button .dct-icon .dct-message .dct-message-icon .dct-message-body .dct-message-title .dct-message-content .dct-message-actions .dct-card .dct-card-media .dct-card-body .dct-card-title .dct-card-subtitle .dct-card-content .dct-card-actions .dct-table-wrap .dct-table .dct-table-caption .dct-breadcrumb .dct-breadcrumb-list .dct-breadcrumb-item .dct-tabs .dct-tabs-list .dct-tab .dct-pagination .dct-pagination-list .dct-pagination-link .dct-block .dct-inline .dct-flex .dct-inline-flex .dct-hidden .dct-text-start .dct-text-center .dct-text-end .dct-text-muted .dct-gap-1 .dct-gap-2 .dct-gap-3 .dct-gap-4 .dct-gap-5 .dct-gap-6 .dct-sm-block .dct-sm-inline .dct-sm-flex .dct-sm-inline-flex .dct-sm-hidden .dct-md-block .dct-md-inline .dct-md-flex .dct-md-inline-flex .dct-md-hidden .dct-lg-block .dct-lg-inline .dct-lg-flex .dct-lg-inline-flex .dct-lg-hidden .dct-field .dct-field-group .dct-fieldset .dct-legend .dct-label .dct-help .dct-input .dct-textarea .dct-select .dct-choice .dct-content .dct-badge .dct-progress .dct-avatar .dct-sidebar-layout .dct-sidebar .dct-main .dct-skip-link .dct-navbar .dct-navbar-brand .dct-navbar-toggle .dct-navbar-menu .dct-navbar-item .dct-navbar-actions .dct-menu .dct-menu-list .dct-menu-item .dct-dialog .dct-dialog-header .dct-dialog-title .dct-dialog-body .dct-dialog-actions .dct-accordion .dct-disclosure .dct-disclosure-summary .dct-disclosure-content .dct-popover .dct-input-group .dct-input-addon .dct-switch .dct-file .dct-p-0 .dct-p-1 .dct-p-2 .dct-p-3 .dct-p-4 .dct-p-5 .dct-p-6 .dct-mb-0 .dct-mb-1 .dct-mb-2 .dct-mb-3 .dct-mb-4 .dct-mb-5 .dct-mb-6 .dct-items-center .dct-justify-between .dct-wrap .dct-grow .dct-w-full .dct-max-w-full .dct-overflow-auto .dct-overflow-hidden .dct-sr-only
NAMES);
    $modifiers12 = names_from_lines('.dct-is-primary .dct-is-secondary .dct-is-danger .dct-is-info .dct-is-warning .dct-is-elevated .dct-is-small .dct-is-medium .dct-is-large .dct-is-fullwidth .dct-is-fullwidth-mobile .dct-is-loading .dct-is-error .dct-is-success .dct-is-striped .dct-is-hoverable .dct-is-compact .dct-is-numeric .dct-is-selected');
    $attributes12 = names_from_lines('data-dct-theme type disabled readonly required hidden open href id for alt tabindex aria-label aria-labelledby aria-describedby aria-hidden aria-disabled aria-busy aria-invalid aria-current aria-expanded aria-controls aria-sort role scope method popover popovertarget max value');
    $tokens12 = names_from_lines(<<<'NAMES'
--dct-color-background --dct-color-surface --dct-color-text --dct-color-text-muted --dct-color-border --dct-color-accent --dct-color-accent-contrast --dct-color-danger --dct-color-danger-contrast --dct-color-success --dct-color-info --dct-color-warning --dct-color-focus --dct-font-family-sans --dct-font-size-base --dct-line-height-base --dct-space-1 --dct-space-2 --dct-space-3 --dct-space-4 --dct-space-5 --dct-space-6 --dct-radius-sm --dct-radius-md --dct-radius-lg --dct-focus-width --dct-focus-offset --dct-shadow-md --dct-container-max --dct-container-gutter --dct-stack-gap --dct-cluster-gap --dct-cluster-align --dct-cluster-justify --dct-grid-min --dct-grid-gap --dct-card-padding --dct-card-gap --dct-card-media-ratio --dct-table-min --dct-table-cell-padding
NAMES);
    foreach (['classNames' => $classes12, 'modifierNames' => $modifiers12, 'attributeNames' => $attributes12, 'tokens' => $tokens12] as $section => $stableNames) {
        $missing = array_diff($stableNames, $manifest[$section]);
        if ($missing !== []) {
            fail('The 1.2 public API is not preserved in ' . $section . ': ' . implode(', ', $missing));
        }
    }
    $contracts12 = [
        'classes' => array_values(array_intersect_key($manifest['classesByName'], array_fill_keys($classes12, true))),
        'modifiers' => array_values(array_intersect_key($manifest['modifiersByName'], array_fill_keys($modifiers12, true))),
        'attributes' => array_values(array_intersect_key($manifest['attributesByName'], array_fill_keys($attributes12, true))),
    ];
    foreach ($contracts12['modifiers'] as &$contract) {
        if (($contract['name'] ?? null) === '.dct-is-elevated') {
            $contract['appliesTo'] = ['.dct-card'];
        }
    }
    unset($contract);
    canonicalize_contract($contracts12);
    $contracts12Hash = hash('sha256', json_encode($contracts12, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    if ($contracts12Hash !== 'ead2a50048b2cdbd1945ddd8baa01dc7fec5df99c5541691eee18ef21d61961e') {
        fail('A structural class, modifier or attribute contract from dctcss 1.2 has changed.');
    }
    if (!in_array('dct-spin', $manifest['animations'], true)) {
        fail('The 1.2 dct-spin animation is not preserved.');
    }
    $themeValues = $manifest['attributesByName']['data-dct-theme']['values'] ?? [];
    if (!is_array($themeValues) || array_diff(['light', 'dark'], $themeValues) !== []) {
        fail('data-dct-theme must preserve the light and dark values.');
    }

    foreach (['.dct-display', '.dct-title', '.dct-subtitle', '.dct-meta'] as $name) {
        if (!isset($manifest['classesByName'][$name])) {
            fail("Release 1.4 typography class is missing: $name");
        }
    }
    $iconButton = $manifest['modifiersByName']['.dct-is-icon-only'] ?? null;
    if (!is_array($iconButton) || !in_array('.dct-button', $iconButton['appliesTo'] ?? [], true)) {
        fail('.dct-is-icon-only must apply to .dct-button.');
    }
    $accessibleName = $iconButton['requiresOneOfAttributes'] ?? [];
    exact_set($accessibleName, ['aria-label', 'aria-labelledby'], '.dct-is-icon-only accessible-name alternatives');
    $density = $manifest['attributesByName']['data-dct-density'] ?? null;
    if (!is_array($density) || ($density['value'] ?? null) !== 'compact') {
        fail('data-dct-density must expose the single compact value.');
    }

    $newTokens = names_from_lines(<<<'NAMES'
--dct-font-size-sm --dct-font-size-lg --dct-font-size-xl --dct-font-size-2xl --dct-font-size-3xl --dct-font-family-mono --dct-font-weight-normal --dct-font-weight-medium --dct-font-weight-bold --dct-line-height-tight --dct-line-height-relaxed --dct-content-measure --dct-control-height --dct-control-padding-block --dct-control-padding-inline --dct-control-gap --dct-panel-padding --dct-navigation-padding-block --dct-navigation-padding-inline --dct-duration-fast --dct-duration-normal --dct-easing-standard --dct-color-surface-subtle --dct-color-surface-raised --dct-color-surface-hover --dct-color-border-subtle --dct-color-border-strong --dct-color-accent-soft --dct-color-success-soft --dct-color-info-soft --dct-color-warning-soft --dct-color-danger-soft --dct-color-success-contrast --dct-color-info-contrast --dct-color-warning-contrast --dct-color-accent-hover --dct-color-danger-hover --dct-shadow-sm --dct-shadow-lg
NAMES);
    $missingTokens = array_diff($newTokens, $manifest['tokens']);
    if ($missingTokens !== []) {
        fail('Release 1.4 tokens are missing: ' . implode(', ', $missingTokens));
    }

    if (version_compare($version, '1.5.0', '<')) {
        return;
    }
    $inverse = $manifest['modifiersByName']['.dct-is-inverse'] ?? null;
    if (!is_array($inverse) || ($inverse['appliesTo'] ?? null) !== ['.dct-navbar']) {
        fail('.dct-is-inverse must apply only to .dct-navbar.');
    }
    $elevated = $manifest['modifiersByName']['.dct-is-elevated'] ?? null;
    if (!is_array($elevated)) {
        fail('.dct-is-elevated is missing.');
    }
    exact_set($elevated['appliesTo'] ?? [], ['.dct-card', '.dct-table-wrap'], '.dct-is-elevated targets');
    $tokens15 = names_from_lines('--dct-navbar-inverse-background --dct-navbar-inverse-text --dct-navbar-inverse-text-muted --dct-navbar-inverse-hover');
    if (array_diff($tokens15, $manifest['tokens']) !== []) {
        fail('Release 1.5 inverse navbar tokens are missing.');
    }
}
