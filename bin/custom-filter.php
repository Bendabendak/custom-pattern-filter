<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use CustomPatternFilter\App;

$app = new Application('Custom Pattern Filter', '1.0.0');
$app->add(new App());
$app->run();
