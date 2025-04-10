<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application('Custom Pattern Filter', '1.0.0');
$app->add(new CustomPatternFilter\App());
$app->add(new \CustomPatternFilter\Command\ViewSummaryCommand());
$app->run();
