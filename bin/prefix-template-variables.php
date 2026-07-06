#!/usr/bin/env php
<?php

/**
 * Prefix unprefixed variables in template PHP files for PHPCS compliance.
 *
 * Usage:
 *   php bin/prefix-template-variables.php
 */

$root = dirname(__DIR__);
$targets = [
    $root . '/templates',
    $root . '/pro/templates',
];

$skipVars = [
    'this',
    'GLOBALS',
    'wpdb',
    '_GET',
    '_POST',
    '_REQUEST',
    '_SERVER',
    '_COOKIE',
    '_FILES',
    '_ENV',
    '_SESSION',
];

/**
 * @param string $name Variable name without leading $.
 */
function addonext_should_skip_template_var(string $name): bool
{
    global $skipVars;

    if (in_array($name, $skipVars, true)) {
        return true;
    }

    return strncmp($name, 'addonext_', 8) === 0;
}

/**
 * @return list<string>
 */
function addonext_collect_template_files(string $directory): array
{
    if (!is_dir($directory)) {
        return [];
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );
    $files = [];

    foreach ($iterator as $fileInfo) {
        if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
            continue;
        }
        $files[] = $fileInfo->getPathname();
    }

    sort($files);

    return $files;
}

/**
 * @param list<int|string> $tokens
 */
function addonext_is_static_property_variable(array $tokens, int $index): bool
{
    for ($cursor = $index - 1; $cursor >= 0; $cursor--) {
        $token = $tokens[$cursor];
        if (!is_array($token)) {
            if ($token === ':') {
                return true;
            }

            if (trim((string) $token) === '') {
                continue;
            }

            return false;
        }

        if (in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            continue;
        }

        return false;
    }

    return false;
}

function addonext_prefix_template_file(string $path): bool
{
    $code = file_get_contents($path);
    if ($code === false) {
        fwrite(STDERR, "Could not read {$path}\n");
        return false;
    }

    $tokens = token_get_all($code);
    $renameMap = [];

    foreach ($tokens as $index => $token) {
        if (!is_array($token) || $token[0] !== T_VARIABLE) {
            continue;
        }

        if (addonext_is_static_property_variable($tokens, (int) $index)) {
            continue;
        }

        $name = substr($token[1], 1);
        if (addonext_should_skip_template_var($name)) {
            continue;
        }

        $renameMap[$name] = 'addonext_' . $name;
    }

    if ($renameMap === []) {
        return false;
    }

    uksort(
        $renameMap,
        static fn (string $left, string $right): int => strlen($right) <=> strlen($left)
    );

    $updated = $code;

    foreach ($renameMap as $oldName => $newName) {
        $updated = preg_replace(
            '/(?<!::)\$' . preg_quote($oldName, '/') . '\b/',
            '$' . $newName,
            $updated
        ) ?? $updated;

        $updated = preg_replace(
            '/@var\s+([^\$]+)\$' . preg_quote($oldName, '/') . '\b/',
            '@var $1$' . $newName,
            $updated
        ) ?? $updated;
    }

    if ($updated === $code) {
        return false;
    }

    file_put_contents($path, $updated);

    return true;
}

$changed = 0;

foreach ($targets as $target) {
    foreach (addonext_collect_template_files($target) as $file) {
        if (addonext_prefix_template_file($file)) {
            ++$changed;
            echo "Prefixed variables in {$file}\n";
        }
    }
}

echo "Updated {$changed} template file(s).\n";
