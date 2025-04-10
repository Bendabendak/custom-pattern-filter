<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Formatter\Formats;

use CustomPatternFilter\FilterResult;
use CustomPatternFilter\Formatter\BaseFormatter;

class MarkdownFormat extends BaseFormatter
{
    public static function getName(): string
    {
        return 'md';
    }

    protected function generateContent(FilterResult $result): string
    {
        $lines = [];
        $lines[] = "# Pattern Match Report";
        $lines[] = "- **Lines Read**: `{$result->linesRead}`";
        $lines[] = "- **Input Source**: `{$result->inputSource}`";
        $lines[] = "- **Timestamp**: `{$result->timestamp->format('Y-m-d H:i:s')}`";
        $lines[] = "";
        $lines[] = "| Pattern | Count |";
        $lines[] = "|---------|-------|";

        foreach ($result->patterns as $pattern => $count) {
            $lines[] = sprintf("| `%s` | %d |", $pattern, $count);
        }

        return implode("\n", $lines) . "\n";
    }
}
