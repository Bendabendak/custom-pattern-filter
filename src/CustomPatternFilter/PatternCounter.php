<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

class PatternCounter
{
    public static function count(array $lines, array $patterns): array
    {
        $results = [];
        foreach ($patterns as $pattern) {
            $results[$pattern] = 0;
        }

        foreach ($lines as $line) {
            foreach ($patterns as $pattern) {
                if (@preg_match_all(
                    pattern: $pattern,
                    subject: $line,
                    matches: $matches
                )) {
                    $results[$pattern] += count($matches[0]);
                }
            }
        }

        return $results;
    }
}
