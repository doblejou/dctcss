<?php

declare(strict_types=1);

final class DctcssContractValidator
{
    private array $classes;
    private array $modifiers;
    private array $attributes;
    private array $diagnostics = [];

    public function __construct(private array $manifest)
    {
        $this->classes = array_column($manifest['classes'], null, 'name');
        $this->modifiers = array_column($manifest['modifiers'], null, 'name');
        $this->attributes = array_column($manifest['attributes'], null, 'name');
    }

    public function validate(string $path): array
    {
        $this->diagnostics = [];
        $source = file_get_contents($path);
        if ($source === false) {
            throw new RuntimeException("Cannot read HTML file: $path");
        }
        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        $loaded = $document->loadHTML($source, LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        if (!$loaded) {
            throw new RuntimeException("Cannot parse HTML file: $path");
        }

        $elements = iterator_to_array($document->getElementsByTagName('*'));
        $ids = [];
        foreach ($elements as $element) {
            if (!$element instanceof DOMElement || !$element->hasAttribute('id')) {
                continue;
            }
            $id = $element->getAttribute('id');
            if ($id === '' || isset($ids[$id])) {
                $this->error($path, $element, 'DCT_DUPLICATE_ID', "Empty or duplicate id: $id");
            } else {
                $ids[$id] = $element;
            }
        }

        foreach ($elements as $element) {
            if (!$element instanceof DOMElement) {
                continue;
            }
            $this->validateElement($path, $element, $elements, $ids);
        }
        return $this->diagnostics;
    }

    private function validateElement(string $path, DOMElement $element, array $elements, array $ids): void
    {
        $tag = strtolower($element->tagName);
        foreach ($this->classNames($element) as $className) {
            if (!str_starts_with($className, 'dct-')) {
                continue;
            }
            $publicName = ".$className";
            if (isset($this->classes[$publicName])) {
                $contract = $this->classes[$publicName];
                if (isset($contract['elements']) && !in_array($tag, $contract['elements'], true)) {
                    $this->error($path, $element, 'DCT_INVALID_ELEMENT', "$publicName is not allowed on <$tag>.");
                }
                if (isset($contract['allowedTypes']) && $tag === 'input') {
                    $type = strtolower($element->getAttribute('type') ?: 'text');
                    if (!in_array($type, $contract['allowedTypes'], true)) {
                        $this->error($path, $element, 'DCT_INVALID_INPUT_TYPE', "$publicName does not allow input type $type.");
                    }
                }
                foreach ($contract['requiresClasses'] ?? [] as $required) {
                    if (!$this->hasClass($element, $required)) {
                        $this->error($path, $element, 'DCT_REQUIRED_CLASS', "$publicName requires $required on the same element.");
                    }
                }
                $this->validateCommon($path, $element, $publicName, $contract);
                foreach ($contract['requiresAncestor'] ?? [] as $required) {
                    if (!$this->hasAncestor($element, $required)) {
                        $this->error($path, $element, 'DCT_REQUIRED_ANCESTOR', "$publicName requires ancestor $required.");
                    }
                }
                foreach ($contract['requiresChild'] ?? [] as $required) {
                    if (!$this->hasChild($element, $required)) {
                        $this->error($path, $element, 'DCT_REQUIRED_CHILD', "$publicName requires direct child $required.");
                    }
                }
                if (isset($contract['requiresOneOfChildren'])) {
                    $found = array_filter($contract['requiresOneOfChildren'], fn(string $required): bool => $this->hasChild($element, $required));
                    if ($found === []) {
                        $this->error($path, $element, 'DCT_REQUIRED_CHILD', "$publicName lacks an allowed direct child.");
                    }
                }
                continue;
            }
            if (isset($this->modifiers[$publicName])) {
                $contract = $this->modifiers[$publicName];
                $valid = array_filter($contract['appliesTo'], fn(string $selector): bool => $this->matchesContractSelector($element, $selector));
                if ($valid === []) {
                    $this->error($path, $element, 'DCT_INVALID_MODIFIER', "$publicName is not valid on <$tag>.");
                }
                $this->validateCommon($path, $element, $publicName, $contract);
                foreach ($contract['requiresAttributesByElement'][$tag] ?? [] as $required) {
                    if (!$this->hasAttributeRequirement($element, $required)) {
                        $this->error($path, $element, 'DCT_REQUIRED_ATTRIBUTE', "$publicName on <$tag> requires $required.");
                    }
                }
                continue;
            }
            $this->error($path, $element, 'DCT_UNKNOWN_CLASS', "Unknown dctcss class or modifier: $publicName");
        }

        foreach ($element->attributes as $attribute) {
            $name = strtolower($attribute->name);
            if (!str_starts_with($name, 'data-dct-')) {
                continue;
            }
            $contract = $this->attributes[$name] ?? null;
            if (!is_array($contract)) {
                $this->error($path, $element, 'DCT_UNKNOWN_ATTRIBUTE', "Unknown dctcss attribute: $name");
                continue;
            }
            $allowed = isset($contract['value']) ? [$contract['value']] : ($contract['values'] ?? []);
            if ($allowed !== [] && !in_array($attribute->value, $allowed, true)) {
                $this->error($path, $element, 'DCT_INVALID_ATTRIBUTE_VALUE', "$name has invalid value {$attribute->value}.");
            }
        }

        foreach (['aria-controls', 'aria-labelledby', 'aria-describedby', 'for', 'popovertarget'] as $attribute) {
            if (!$element->hasAttribute($attribute)) {
                continue;
            }
            foreach (preg_split('/\s+/', trim($element->getAttribute($attribute)), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $target) {
                if (!isset($ids[$target])) {
                    $this->error($path, $element, 'DCT_BROKEN_REFERENCE', "Broken $attribute reference: $target");
                }
            }
        }
        if ($this->hasClass($element, '.dct-navbar-toggle')) {
            $target = $ids[$element->getAttribute('aria-controls')] ?? null;
            if (!$target instanceof DOMElement || !$this->hasClass($target, '.dct-navbar-menu')) {
                $this->error($path, $element, 'DCT_NAVBAR_TARGET', '.dct-navbar-toggle must target .dct-navbar-menu.');
            }
        }
        if ($this->hasClass($element, '.dct-popover')) {
            $id = $element->getAttribute('id');
            $found = array_filter($elements, static fn(DOMElement $candidate): bool => self::targetsPopover($candidate, $id));
            if ($found === []) {
                $this->error($path, $element, 'DCT_POPOVER_TRIGGER', '.dct-popover requires a matching popovertarget.');
            }
        }
    }

    private function validateCommon(string $path, DOMElement $element, string $name, array $contract): void
    {
        foreach ($contract['requiresAttributes'] ?? [] as $required) {
            if (!$this->hasAttributeRequirement($element, $required)) {
                $this->error($path, $element, 'DCT_REQUIRED_ATTRIBUTE', "$name requires $required.");
            }
        }
        if (isset($contract['requiresOneOfAttributes'])) {
            $found = array_filter($contract['requiresOneOfAttributes'], fn(string $required): bool => $this->hasAttributeRequirement($element, $required));
            if ($found === []) {
                $this->error($path, $element, 'DCT_REQUIRED_ATTRIBUTE', "$name requires one of " . implode(', ', $contract['requiresOneOfAttributes']) . '.');
            }
        }
        if (isset($contract['requiresDescendantAttributes'])) {
            foreach ($element->getElementsByTagName('*') as $descendant) {
                $found = array_filter($contract['requiresDescendantAttributes'], fn(string $required): bool => $this->hasAttributeRequirement($descendant, $required));
                if (count($found) === count($contract['requiresDescendantAttributes'])) {
                    return;
                }
            }
            $this->error($path, $element, 'DCT_REQUIRED_DESCENDANT', "$name requires one descendant with " . implode(', ', $contract['requiresDescendantAttributes']) . '.');
        }
    }

    private function matchesRequirement(DOMElement $element, string $requirement): bool
    {
        if (preg_match('/^(?<tag>[a-z][a-z0-9-]*)?(?<class>\.dct-[a-z0-9-]+)?(?<attributes>(?:\[[^\]]+\])*)$/', $requirement, $match) !== 1) {
            throw new RuntimeException("Unsupported manifest selector: $requirement");
        }
        if (($match['tag'] ?? '') !== '' && strtolower($element->tagName) !== $match['tag']) {
            return false;
        }
        if (($match['class'] ?? '') !== '' && !$this->hasClass($element, $match['class'])) {
            return false;
        }
        preg_match_all('/\[([a-z][a-z0-9-]*)(?:=([^\]]+))?\]/', $match['attributes'] ?? '', $attributes, PREG_SET_ORDER);
        foreach ($attributes as $attribute) {
            if (!$element->hasAttribute($attribute[1]) || (isset($attribute[2]) && $element->getAttribute($attribute[1]) !== trim($attribute[2], "\"'"))) {
                return false;
            }
        }
        return true;
    }

    private function matchesContractSelector(DOMElement $element, string $selector): bool
    {
        $parts = preg_split('/\s+/', trim($selector), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $current = $element;
        for ($index = count($parts) - 1; $index >= 0; --$index) {
            if ($index === count($parts) - 1) {
                if (!$this->matchesRequirement($current, $parts[$index])) {
                    return false;
                }
                continue;
            }
            $ancestor = $current->parentNode;
            while ($ancestor instanceof DOMElement && !$this->matchesRequirement($ancestor, $parts[$index])) {
                $ancestor = $ancestor->parentNode;
            }
            if (!$ancestor instanceof DOMElement) {
                return false;
            }
            $current = $ancestor;
        }
        return $parts !== [];
    }

    private function hasAncestor(DOMElement $element, string $requirement): bool
    {
        $ancestor = $element->parentNode;
        while ($ancestor instanceof DOMElement) {
            if ($this->matchesRequirement($ancestor, $requirement)) {
                return true;
            }
            $ancestor = $ancestor->parentNode;
        }
        return false;
    }

    private function hasChild(DOMElement $element, string $requirement): bool
    {
        foreach ($element->childNodes as $child) {
            if ($child instanceof DOMElement && $this->matchesRequirement($child, $requirement)) {
                return true;
            }
        }
        return false;
    }

    public static function hasAttributeRequirement(DOMElement $element, string $requirement): bool
    {
        [$name, $value] = array_pad(explode('=', $requirement, 2), 2, null);
        if (in_array($name, ['aria-label', 'aria-labelledby'], true) && trim($element->getAttribute($name)) === '') {
            return false;
        }

        return $element->hasAttribute($name) && ($value === null || $element->getAttribute($name) === $value);
    }

    ################################################
    #
    #  targetsPopover
    #
    #  Identifica los controles nativos asociados al popover.
    #
    ################################################
    public static function targetsPopover(DOMElement $element, string $id): bool
    {
        if ($id === '' || $element->getAttribute('popovertarget') !== $id)
        {
            return false;
        }

        return $element->tagName === 'button'
            || ($element->tagName === 'input' && strtolower($element->getAttribute('type')) === 'button');
    }

    private function classNames(DOMElement $element): array
    {
        return preg_split('/\s+/', trim($element->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }

    private function hasClass(DOMElement $element, string $class): bool
    {
        return in_array(ltrim($class, '.'), $this->classNames($element), true);
    }

    private function error(string $path, DOMElement $element, string $code, string $message): void
    {
        $this->diagnostics[] = ['path' => $path, 'line' => max(1, $element->getLineNo()), 'code' => $code, 'message' => $message];
    }
}

function dctcssLoadManifest(string $path): array
{
    $source = file_get_contents($path);
    if ($source === false) {
        throw new RuntimeException("Cannot read manifest: $path");
    }
    $manifest = json_decode($source, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($manifest) || ($manifest['schemaVersion'] ?? null) !== 2 || ($manifest['name'] ?? null) !== 'dctcss'
        || ($manifest['prefix'] ?? null) !== 'dct-' || !is_array($manifest['classes'] ?? null)
        || !is_array($manifest['modifiers'] ?? null) || !is_array($manifest['attributes'] ?? null)) {
        throw new RuntimeException('Manifest identity or schema is invalid.');
    }
    return $manifest;
}

function dctcssFiles(array $paths): array
{
    $files = [];
    foreach ($paths as $path) {
        if (is_file($path) && !is_link($path)) {
            $files[realpath($path) ?: $path] = true;
            continue;
        }
        if (!is_dir($path) || is_link($path)) {
            throw new RuntimeException("Input path is not a regular file or directory: $path");
        }
        $found = false;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isLink() || !$file->isFile() || !in_array(strtolower($file->getExtension()), ['html', 'htm'], true)) {
                continue;
            }
            $files[$file->getRealPath() ?: $file->getPathname()] = true;
            $found = true;
        }
        if (!$found) {
            throw new RuntimeException("Directory contains no HTML files: $path");
        }
    }
    $result = array_keys($files);
    sort($result);
    return $result;
}

function dctcssMain(array $argv): int
{
    $manifestPath = __DIR__ . '/dctcss-api.json';
    $format = 'text';
    $paths = [];
    for ($index = 1; $index < count($argv); ++$index) {
        $argument = $argv[$index];
        if ($argument === '--help') {
            fwrite(STDOUT, "Usage: php dctcss-validate.php [--manifest FILE] [--format text|json] PATH...\n");
            return 0;
        }
        if ($argument === '--version') {
            $manifest = dctcssLoadManifest($manifestPath);
            fwrite(STDOUT, $manifest['version'] . "\n");
            return 0;
        }
        if ($argument === '--manifest' || $argument === '--format') {
            $value = $argv[++$index] ?? null;
            if ($value === null) {
                throw new InvalidArgumentException("Missing value for $argument");
            }
            if ($argument === '--manifest') {
                $manifestPath = $value;
            } else {
                $format = $value;
            }
            continue;
        }
        if ($argument === '--') {
            $paths = array_merge($paths, array_slice($argv, $index + 1));
            break;
        }
        if (str_starts_with($argument, '-')) {
            throw new InvalidArgumentException("Unknown option: $argument");
        }
        $paths[] = $argument;
    }
    if ($paths === [] || !in_array($format, ['text', 'json'], true)) {
        throw new InvalidArgumentException('At least one path and a valid format are required.');
    }

    $manifest = dctcssLoadManifest($manifestPath);
    $files = dctcssFiles($paths);
    $validator = new DctcssContractValidator($manifest);
    $diagnostics = [];
    foreach ($files as $file) {
        $diagnostics = array_merge($diagnostics, $validator->validate($file));
    }
    if ($format === 'json') {
        fwrite(STDOUT, json_encode([
            'valid' => $diagnostics === [],
            'manifestVersion' => $manifest['version'],
            'filesChecked' => count($files),
            'diagnostics' => $diagnostics,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
    } else {
        foreach ($diagnostics as $diagnostic) {
            fwrite(STDERR, "{$diagnostic['path']}:{$diagnostic['line']}: {$diagnostic['code']}: {$diagnostic['message']}\n");
        }
        fwrite($diagnostics === [] ? STDOUT : STDERR, 'dctcss-validate: ' . count($diagnostics) . ' error(s) in ' . count($files) . " file(s); manifest {$manifest['version']}\n");
    }
    return $diagnostics === [] ? 0 : 1;
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    try {
        exit(dctcssMain($argv));
    } catch (InvalidArgumentException | RuntimeException | JsonException $error) {
        fwrite(STDERR, "dctcss-validate: {$error->getMessage()}\n");
        exit(2);
    }
}
