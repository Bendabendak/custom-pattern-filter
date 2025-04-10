<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Formatter\Formats;

use CustomPatternFilter\Formatter\BaseFormatter;
use CustomPatternFilter\FilterResult;

class TextFormat extends BaseFormatter
{
    public static function getName(): string
    {
        return 'txt';
    }

    protected function generateContent(FilterResult $result): string
    {
        $lines = [
            "Lines read: {$result->linesRead}",
            "Pattern matches:",
        ];

        foreach ($result->patterns as $pattern => $count) {
            $lines[] = "  $pattern: $count";
        }

        return implode("\n", $lines) . "\n";
    }
}
