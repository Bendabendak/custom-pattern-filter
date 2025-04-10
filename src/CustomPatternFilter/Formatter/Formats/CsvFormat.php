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
use Symfony\Component\Console\Output\OutputInterface;

class CsvFormat extends BaseFormatter
{
    public static function getName(): string
    {
        return 'csv';
    }

    protected function generateContent(FilterResult $result): string
    {
        $lines = [];
        $lines[] = 'pattern,count';

        foreach ($result->patterns as $pattern => $count) {
            $escapedPattern = str_replace(
                search: '"',
                replace: '""',
                subject: $pattern
            );
            $lines[] = sprintf('"%s",%d', $escapedPattern, $count);
        }

        return implode("\n", $lines);
    }
}
