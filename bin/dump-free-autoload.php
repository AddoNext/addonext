#!/usr/bin/env php
<?php

/**
 * Regenerate Composer autoload for the wp.org free zip after pro/ is removed.
 *
 * Usage:
 *   php bin/dump-free-autoload.php [plugin-root]
 *
 * When plugin-root is omitted, uses the parent directory of this script's folder.
 */

$root = isset($argv[1]) && is_dir($argv[1]) ? realpath($argv[1]) : dirname(__DIR__);
$composerFile = $root . '/composer.json';
$tempFile = $root . '/composer.free.json';

if (!is_readable($composerFile)) {
    fwrite(STDERR, "composer.json not found.\n");
    exit(1);
}

$config = json_decode((string) file_get_contents($composerFile), true, 512, JSON_THROW_ON_ERROR);
$psr4 = $config['autoload']['psr-4'] ?? [];

unset($psr4['Veloria\\Pro\\'], $psr4['Veloria\\Pro\\Migrations\\']);

foreach (['Veloria\\Widgets\\', 'Veloria\\Extensions\\'] as $namespace) {
    if (!isset($psr4[$namespace])) {
        continue;
    }

    $paths = (array) $psr4[$namespace];
    $psr4[$namespace] = array_values(
        array_filter(
            $paths,
            static fn (string $path): bool => strpos($path, 'pro/') !== 0
        )
    );
}

$config['autoload']['psr-4'] = $psr4;

file_put_contents(
    $tempFile,
    json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$command = sprintf(
    'COMPOSER=%s composer dump-autoload -o -d %s',
    escapeshellarg($tempFile),
    escapeshellarg($root)
);

passthru($command, $exitCode);
unlink($tempFile);

exit($exitCode);
