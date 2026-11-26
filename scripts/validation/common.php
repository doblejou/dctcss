<?php

declare(strict_types=1);

final class ValidationFailure extends RuntimeException
{
}

function fail(string $message): never
{
    throw new ValidationFailure($message);
}

function read_file(string $path): string
{
    $contents = @file_get_contents($path);
    if ($contents === false) {
        fail("Cannot read $path");
    }
    return $contents;
}

function string_list(mixed $value, string $label, bool $allowEmpty = false): array
{
    if (!is_array($value) || !array_is_list($value) || (!$allowEmpty && $value === [])) {
        fail("$label must be a" . ($allowEmpty ? '' : ' non-empty') . ' array.');
    }
    foreach ($value as $item) {
        if (!is_string($item) || $item === '') {
            fail("$label must contain non-empty strings.");
        }
    }
    if (count($value) !== count(array_unique($value))) {
        fail("$label contains duplicates.");
    }
    return array_values($value);
}

function exact_keys(array $value, array $required, array $optional, string $label): void
{
    $keys = array_keys($value);
    $missing = array_diff($required, $keys);
    $unknown = array_diff($keys, array_merge($required, $optional));
    if ($missing !== []) {
        fail("$label is missing fields: " . implode(', ', $missing));
    }
    if ($unknown !== []) {
        fail("$label has unknown fields: " . implode(', ', $unknown));
    }
}

function exact_set(array $actual, array $expected, string $label): void
{
    sort($actual);
    sort($expected);
    if ($actual === $expected) {
        return;
    }
    $missing = array_diff($expected, $actual);
    $extra = array_diff($actual, $expected);
    $details = [];
    if ($missing !== []) {
        $details[] = 'missing ' . implode(', ', $missing);
    }
    if ($extra !== []) {
        $details[] = 'unexpected ' . implode(', ', $extra);
    }
    fail("$label differs: " . implode('; ', $details));
}

function css_references(string $value): array
{
    preg_match_all('/\.dct-[a-z0-9-]+/', $value, $matches);
    return array_values(array_unique($matches[0]));
}

function directory_entries(string $path): array
{
    $entries = @scandir($path);
    if ($entries === false) {
        fail("Cannot read directory $path");
    }
    return array_values(array_diff($entries, ['.', '..']));
}

function path_is_within(string $path, string $root): bool
{
    $root = rtrim($root, DIRECTORY_SEPARATOR);
    return $path === $root || str_starts_with($path, $root . DIRECTORY_SEPARATOR);
}
