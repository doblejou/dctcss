<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/dctcss-validate.php';

function load_html(string $path): array
{
    $html = read_file($path);
    $previous = libxml_use_internal_errors(true);
    $document = new DOMDocument();
    $loaded = $document->loadHTML($html, LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (!$loaded) {
        fail("Cannot parse generated HTML: $path");
    }
    return [$document, $html];
}

function public_reference(
    string $reference,
    string $attribute,
    string $slug,
    string $path,
    string $publicDirectory,
    array $idsByPage,
): void {
    $reference = trim($reference);
    if ($reference === '' || preg_match('~^(?:[a-z][a-z0-9+.-]*:|//|/)~i', $reference) === 1) {
        fail("Empty, root-relative or remote $attribute in $path: $reference");
    }
    $urlPath = rawurldecode((string) parse_url($reference, PHP_URL_PATH));
    $fragment = rawurldecode((string) parse_url($reference, PHP_URL_FRAGMENT));
    $targetSlug = $slug;
    if ($urlPath !== '') {
        if (in_array('..', explode('/', str_replace('\\', '/', $urlPath)), true)) {
            fail("Escaping local reference in $path: $reference");
        }
        $targetPath = realpath($publicDirectory . DIRECTORY_SEPARATOR . $urlPath);
        $publicRoot = realpath($publicDirectory);
        if ($targetPath === false || $publicRoot === false || !is_file($targetPath) || !path_is_within($targetPath, $publicRoot)) {
            fail("Missing or escaping local reference in $path: $reference");
        }
        $targetSlug = pathinfo($targetPath, PATHINFO_EXTENSION) === 'html' ? basename($targetPath, '.html') : '';
    }
    if ($fragment !== '' && ($targetSlug === '' || !isset($idsByPage[$targetSlug][$fragment]))) {
        fail("Missing fragment target in $path: $reference");
    }
}

function element_class_names(DOMElement $element): array
{
    return preg_split('/\s+/', trim($element->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY) ?: [];
}

function element_has_class(DOMElement $element, string $class): bool
{
    return in_array(ltrim($class, '.'), element_class_names($element), true);
}

function validate_reference_application(DOMDocument $document, string $path): void
{
    $xpath = new DOMXPath($document);
    $root = $xpath->query('//*[@id="application-preview"]/*[1]')?->item(0);
    if (!$root instanceof DOMElement) {
        fail("Reference application lacks a distinct framework-only root in $path.");
    }
    foreach (array_merge([$root], iterator_to_array($root->getElementsByTagName('*'))) as $element) {
        if (!$element instanceof DOMElement) {
            continue;
        }
        if ($element->hasAttribute('style')) {
            fail("Reference application contains an inline style in $path.");
        }
        if (strtolower($element->tagName) === 'form' && $element->getAttribute('action') !== '') {
            fail("Reference application contains a navigable preview form in $path.");
        }
        foreach (element_class_names($element) as $className) {
            if (!str_starts_with($className, 'dct-')) {
                fail("Reference application contains a private class in $path: .$className");
            }
        }
        foreach ($element->attributes as $attribute) {
            if (str_starts_with(strtolower($attribute->name), 'data-') && !str_starts_with(strtolower($attribute->name), 'data-dct-')) {
                fail("Reference application contains a private data attribute in $path: {$attribute->name}");
            }
        }
    }
    $required = [
        'navigation' => ['.dct-navbar'],
        'page title' => ['.dct-display', '.dct-title'],
        'table' => ['.dct-table'],
        'form control' => ['.dct-input', '.dct-select'],
        'message' => ['.dct-message'],
        'dialog' => ['.dct-dialog'],
    ];
    foreach ($required as $label => $classes) {
        $found = false;
        foreach ($classes as $class) {
            $query = './/*[contains(concat(" ", normalize-space(@class), " "), " ' . ltrim($class, '.') . ' ")]';
            $found = $found || ($xpath->query($query, $root)?->length ?? 0) > 0;
        }
        if (!$found) {
            fail("Reference application lacks its $label in $path.");
        }
    }
    $cards = $xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " dct-card ")]', $root);
    if (($cards?->length ?? 0) < 3) {
        fail("Reference application must compose at least three KPI/content cards in $path.");
    }
    if (($xpath->query('.//*[@data-dct-density="compact"]', $root)?->length ?? 0) === 0) {
        fail("Reference application must demonstrate compact density in $path.");
    }
}

function normalize_css_value(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? $value);
}

function validate_documented_token_values(string $root, DOMDocument $fundamentals, array $manifest): void
{
    $source = '';
    foreach (preg_split('/\R/', read_file("$root/src/dct.css")) ?: [] as $line) {
        if (preg_match('~^/\* dct-include: ((?:[a-z0-9-]+/)*[a-z0-9-]+\.css) \*/$~', $line, $match) === 1) {
            $source .= "\n" . read_file("$root/src/{$match[1]}");
        }
    }
    $source = preg_replace('~/\*.*?\*/~s', '', $source) ?? $source;
    preg_match_all('/(?<token>--dct-[a-z0-9-]+)\s*:\s*(?<value>[^;{}]+)\s*;/i', $source, $declarations, PREG_SET_ORDER);
    $defaults = [];
    foreach ($declarations as $declaration) {
        $defaults[$declaration['token']] ??= normalize_css_value($declaration['value']);
    }

    $documented = [];
    $xpath = new DOMXPath($fundamentals);
    foreach ($xpath->query('//tr[td[1]/code and td[2]/code]') ?: [] as $row) {
        if (!$row instanceof DOMElement) {
            continue;
        }
        $cells = $row->getElementsByTagName('td');
        $token = trim($cells->item(0)?->textContent ?? '');
        if (!str_starts_with($token, '--dct-')) {
            continue;
        }
        if (isset($documented[$token])) {
            fail("Fundamentals documents a token value more than once: $token");
        }
        $documented[$token] = normalize_css_value($cells->item(1)?->textContent ?? '');
    }
    exact_set(array_keys($documented), $manifest['tokens'], 'Tokens with documented default values');
    foreach ($documented as $token => $value) {
        if (!isset($defaults[$token])) {
            fail("Public token has no source declaration: $token");
        }
        if ($value !== $defaults[$token]) {
            fail("Documented value for $token differs from CSS: $value (documented), {$defaults[$token]} (source).");
        }
    }
}

function element_matches_requirement(DOMElement $element, string $requirement): bool
{
    if (preg_match('/^(?<tag>[a-z][a-z0-9-]*)?(?<class>\.dct-[a-z0-9-]+)?(?<attributes>(?:\[[^\]]+\])*)$/', $requirement, $match) !== 1) {
        fail("Unsupported manifest selector: $requirement");
    }
    if (($match['tag'] ?? '') !== '' && strtolower($element->tagName) !== $match['tag']) {
        return false;
    }
    if (($match['class'] ?? '') !== '' && !element_has_class($element, $match['class'])) {
        return false;
    }
    preg_match_all('/\[([a-z][a-z0-9-]*)(?:=([^\]]+))?\]/', $match['attributes'] ?? '', $attributes, PREG_SET_ORDER);
    foreach ($attributes as $attribute) {
        $name = $attribute[1];
        if (!$element->hasAttribute($name)) {
            return false;
        }
        if (isset($attribute[2]) && $element->getAttribute($name) !== trim($attribute[2], "\"'")) {
            return false;
        }
    }
    return true;
}

function element_matches_contract_selector(DOMElement $element, string $selector): bool
{
    $parts = preg_split('/\s+/', trim($selector), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $current = $element;
    for ($index = count($parts) - 1; $index >= 0; --$index) {
        if ($index === count($parts) - 1) {
            if (!element_matches_requirement($current, $parts[$index])) {
                return false;
            }
            continue;
        }
        $ancestor = $current->parentNode;
        while ($ancestor instanceof DOMElement && !element_matches_requirement($ancestor, $parts[$index])) {
            $ancestor = $ancestor->parentNode;
        }
        if (!$ancestor instanceof DOMElement) {
            return false;
        }
        $current = $ancestor;
    }
    return $parts !== [];
}

function element_has_child(DOMElement $element, string $requirement): bool
{
    foreach ($element->childNodes as $child) {
        if ($child instanceof DOMElement && element_matches_requirement($child, $requirement)) {
            return true;
        }
    }
    return false;
}

function element_matches_attribute_requirement(DOMElement $element, string $requirement): bool
{
    return DctcssContractValidator::hasAttributeRequirement($element, $requirement);
}

function validate_required_attributes(DOMElement $element, array $requirements, string $label): void
{
    foreach ($requirements as $requirement) {
        if (!element_matches_attribute_requirement($element, $requirement)) {
            fail("$label requires $requirement.");
        }
    }
}

function validate_common_contract_requirements(
    DOMElement $element,
    array $contract,
    string $publicName,
    string $label,
): void
{
    validate_required_attributes($element, $contract['requiresAttributes'] ?? [], "$publicName in $label");
    if (isset($contract['requiresOneOfAttributes'])) {
        $found = array_filter(
            $contract['requiresOneOfAttributes'],
            static fn(string $requirement): bool => element_matches_attribute_requirement($element, $requirement),
        );
        if ($found === []) {
            fail("$publicName requires one of " . implode(', ', $contract['requiresOneOfAttributes']) . " in $label.");
        }
    }
    if (isset($contract['requiresDescendantAttributes'])) {
        foreach ($element->getElementsByTagName('*') as $descendant) {
            if (!$descendant instanceof DOMElement) {
                continue;
            }
            $matches = array_filter(
                $contract['requiresDescendantAttributes'],
                static fn(string $requirement): bool => element_matches_attribute_requirement($descendant, $requirement),
            );
            if (count($matches) === count($contract['requiresDescendantAttributes'])) {
                return;
            }
        }
        fail("$publicName requires one descendant with " . implode(', ', $contract['requiresDescendantAttributes']) . " in $label.");
    }
}

function validate_copyable_snippet(string $source, array $manifest, string $label): void
{
    if (!str_contains($source, '<')) {
        return;
    }
    $previous = libxml_use_internal_errors(true);
    $document = new DOMDocument();
    $loaded = $document->loadHTML(
        '<!doctype html><html><body><div id="dct-snippet-root">' . $source . '</div></body></html>',
        LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR,
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (!$loaded) {
        fail("Cannot parse copyable snippet: $label");
    }
    $root = $document->getElementById('dct-snippet-root');
    if (!$root instanceof DOMElement) {
        fail("Cannot locate copyable snippet root: $label");
    }

    $ids = [];
    foreach ($root->getElementsByTagName('*') as $element) {
        if (!$element instanceof DOMElement) {
            continue;
        }
        if ($element->hasAttribute('id')) {
            $id = $element->getAttribute('id');
            if ($id === '' || isset($ids[$id])) {
                fail("Copyable snippet has an empty or duplicate id in $label: $id");
            }
            $ids[$id] = $element;
        }
    }

    foreach ($root->getElementsByTagName('*') as $element) {
        if (!$element instanceof DOMElement) {
            continue;
        }
        $tag = strtolower($element->tagName);
        foreach (element_class_names($element) as $className) {
            $publicName = ".$className";
            if (isset($manifest['classesByName'][$publicName])) {
                $contract = $manifest['classesByName'][$publicName];
                if (isset($contract['elements']) && !in_array($tag, $contract['elements'], true)) {
                    fail("$publicName is not allowed on <$tag> in $label.");
                }
                if (isset($contract['allowedTypes']) && $tag === 'input') {
                    $type = strtolower($element->getAttribute('type') ?: 'text');
                    if (!in_array($type, $contract['allowedTypes'], true)) {
                        fail("$publicName does not allow input type $type in $label.");
                    }
                }
                foreach ($contract['requiresClasses'] ?? [] as $requiredClass) {
                    if (!element_has_class($element, $requiredClass)) {
                        fail("$publicName requires $requiredClass on the same element in $label.");
                    }
                }
                validate_common_contract_requirements($element, $contract, $publicName, $label);
                foreach ($contract['requiresAncestor'] ?? [] as $requirement) {
                    $ancestor = $element->parentNode;
                    while ($ancestor instanceof DOMElement && !element_matches_requirement($ancestor, $requirement)) {
                        $ancestor = $ancestor->parentNode;
                    }
                    if (!$ancestor instanceof DOMElement) {
                        fail("$publicName requires ancestor $requirement in $label.");
                    }
                }
                foreach ($contract['requiresChild'] ?? [] as $requirement) {
                    if (!element_has_child($element, $requirement)) {
                        fail("$publicName requires child $requirement in $label.");
                    }
                }
                if (isset($contract['requiresOneOfChildren'])) {
                    $found = array_filter($contract['requiresOneOfChildren'], static fn(string $requirement): bool => element_has_child($element, $requirement));
                    if ($found === []) {
                        fail("$publicName lacks an allowed child in $label.");
                    }
                }
                continue;
            }
            if (isset($manifest['modifiersByName'][$publicName])) {
                $contract = $manifest['modifiersByName'][$publicName];
                $applies = array_filter($contract['appliesTo'], static fn(string $selector): bool => element_matches_contract_selector($element, $selector));
                if ($applies === []) {
                    fail("$publicName is not valid on <$tag> in $label.");
                }
                validate_common_contract_requirements($element, $contract, $publicName, $label);
                if (isset($contract['requiresAttributesByElement'][$tag])) {
                    validate_required_attributes($element, $contract['requiresAttributesByElement'][$tag], "$publicName on <$tag> in $label");
                }
                continue;
            }
            if (str_starts_with($className, 'dct-')) {
                fail("$label contains an uninventoried dctcss class or modifier: $publicName");
            }
        }
        foreach ($element->attributes as $attribute) {
            $name = strtolower($attribute->name);
            if (!str_starts_with($name, 'data-dct-')) {
                continue;
            }
            $contract = $manifest['attributesByName'][$name] ?? null;
            if (!is_array($contract)) {
                fail("$label contains an uninventoried dctcss attribute: $name");
            }
            $allowed = isset($contract['value']) ? [$contract['value']] : ($contract['values'] ?? []);
            if ($allowed !== [] && !in_array($attribute->value, $allowed, true)) {
                fail("$name has an invalid value in $label: {$attribute->value}");
            }
        }
        foreach (['aria-controls', 'aria-labelledby', 'aria-describedby', 'for', 'popovertarget'] as $attribute) {
            if (!$element->hasAttribute($attribute)) {
                continue;
            }
            foreach (preg_split('/\s+/', trim($element->getAttribute($attribute)), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $target) {
                if (!isset($ids[$target])) {
                    fail("Copyable snippet has a broken $attribute reference in $label: $target");
                }
            }
        }
        if (element_has_class($element, '.dct-navbar-toggle')) {
            $target = $ids[$element->getAttribute('aria-controls')] ?? null;
            if (!$target instanceof DOMElement || !element_has_class($target, '.dct-navbar-menu')) {
                fail(".dct-navbar-toggle must target .dct-navbar-menu in $label.");
            }
        }
        if (element_has_class($element, '.dct-popover')) {
            $id = $element->getAttribute('id');
            $triggerFound = false;
            foreach ($root->getElementsByTagName('*') as $candidate) {
                if ($candidate instanceof DOMElement && DctcssContractValidator::targetsPopover($candidate, $id)) {
                    $triggerFound = true;
                    break;
                }
            }
            if (!$triggerFound) {
                fail(".dct-popover requires a matching popovertarget in $label.");
            }
        }
    }
}

function validate_documented_api(string $contents, array $manifest, string $label): void
{
    $knownClasses = array_fill_keys(array_merge($manifest['classNames'], $manifest['modifierNames']), true);
    $knownTokens = array_fill_keys($manifest['tokens'], true);
    $knownAnimations = array_fill_keys($manifest['animations'], true);
    foreach ($manifest['unavailable'] as $name) {
        if (str_starts_with($name, '.dct-')) {
            $knownClasses[$name] = true;
        } elseif (str_starts_with($name, '--dct-')) {
            $knownTokens[$name] = true;
        } elseif (str_starts_with($name, 'dct-')) {
            $knownAnimations[$name] = true;
        }
    }
    foreach (['dct-reset', 'dct-tokens', 'dct-base', 'dct-layout', 'dct-components', 'dct-utilities', 'dct-themes'] as $layer) {
        $knownAnimations[$layer] = true;
    }
    $patterns = [
        ['~(?<![a-z0-9_-])\.dct-[a-z0-9-]+(?:/[0-9]+)?(?![a-z0-9_-])~i', $knownClasses, 'class or modifier'],
        ['~(?<![a-z0-9_-])--dct-[a-z0-9-]+(?![a-z0-9_-])~i', $knownTokens, 'token'],
        ['~(?<![.a-z0-9_-])dct-[a-z0-9-]+(?![a-z0-9_-])~', $knownAnimations, 'animation'],
    ];
    foreach (preg_split('/\R/', $contents) as $line) {
        $decodedLine = html_entity_decode($line, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        preg_match_all('/\bclass=["\']([^"\']+)["\']/', $decodedLine, $classAttributes);
        foreach ($classAttributes[1] as $classAttribute) {
            foreach (preg_split('/\s+/', trim($classAttribute)) as $className) {
                if (str_starts_with($className, 'dct-') && !isset($knownClasses[".$className"])) {
                    fail("$label contains an uninventoried dctcss class or modifier: .$className");
                }
            }
        }
        foreach ($patterns as [$pattern, $known, $kind]) {
            $searchLine = $kind === 'animation'
                ? preg_replace('/\bclass=["\'][^"\']*["\']/', '', strip_tags($decodedLine))
                : $decodedLine;
            preg_match_all($pattern, $searchLine, $matches);
            foreach (array_unique($matches[0]) as $name) {
                if (isset($known[$name])) {
                    continue;
                }
                $plainLine = strtolower(html_entity_decode(strip_tags($line), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if (preg_match('/\bno\s+(?:existe(?:n)?|inventes|publica(?:n)?|disponible)\b/', $plainLine) === 1) {
                    continue;
                }
                fail("$label contains an uninventoried dctcss $kind: $name");
            }
        }
    }
}
