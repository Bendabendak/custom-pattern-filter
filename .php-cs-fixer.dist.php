<?php

$header = <<<EOF
@package   CustomPatternFilter
@author    Benda Martin <martinbendabendak@seznam.cz>
@license   MIT
@link      https://https://github.com/Bendabendak
@copyright Copyright (c) 2025 Benda Martin
EOF;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/bin',
    ])
    ->name('*.php')
    ->exclude('vendor');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'header_comment' => [
            'header' => $header,
            'separate' => 'top',
            'location' => 'after_open',
            'comment_type' => 'PHPDoc',
        ],
    ])
    ->setFinder($finder);
