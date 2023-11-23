<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

$datePrefix = (new DateTimeImmutable('now'))->format('Ym');
$polyfillsBootstrap = Finder::create()
    ->files()
    ->in(__DIR__.'/vendor/symfony/polyfill-*')
    ->name('*.php');

return [
    'prefix' => 'DEPTRAC_'.$datePrefix,
    'finders' => [
        Finder::create()->files()->in([
            'config',
            'internal',
            'src',
            'vendor',
        ])->append([
            'deptrac',
            'deptrac.config.php',
            'deptrac.php',
            'composer.json',
        ])->exclude([
            'bin',
            'tests',
            'test',
        ])->notName('/.*\\.(xml|md|dist|neon)|Makefile|composer\\.json|composer\\.lock/'),
    ],
    'patchers' => [],
    'tag-declarations-as-internal' => false,
    'exclude-files' => array_map(
        static function ($file) {
            return $file->getPathName();
        },
        iterator_to_array($polyfillsBootstrap)
    ),
    'exclude-namespaces' => [
        'Qossmic\Deptrac',
        'Symfony\Polyfill',
    ],
    'expose-functions' => ['trigger_deprecation'],
    'expose-global-constants' => true,
    'expose-global-classes' => true,
    'expose-global-functions' => true,
];
