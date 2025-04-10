<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */
use CustomPatternFilter\Formatter\Formats\JsonFormat;
use CustomPatternFilter\Formatter\Formats\TextFormat;
use CustomPatternFilter\Formatter\Formats\CsvFormat;
use CustomPatternFilter\Formatter\Formats\MarkdownFormat;

return [
    JsonFormat::class,
    TextFormat::class,
    CsvFormat::class,
    MarkdownFormat::class,
];
