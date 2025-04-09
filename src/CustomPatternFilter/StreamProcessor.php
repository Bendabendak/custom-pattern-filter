<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

class StreamProcessor
{
    public function processStream(
        $stream,
        array $patterns,
        bool $debug = false,
        ?callable $onProgress = null,
        string $inputSource = 'unknown',
        string $command = '',
        array $arguments = []
    ): FilterResult {
        $results = array_fill_keys(
            keys: $patterns,
            value: 0
        );
        $lineCount = 0;

        $matchLine = function (string $line, array &$results): void {
            foreach ($results as $pattern => &$count) {
                if (@preg_match_all(
                    pattern: $pattern,
                    subject: $line,
                    matches: $matches
                )) {
                    $count += count(value: $matches[0]);
                }
            }
        };

        foreach ($this->readLinesFromStream(stream: $stream) as $line) {
            $lineCount++;
            $matchLine(
                line: $line,
                results: $results
            );
            if ($debug) {
                usleep(microseconds: 50000);
            }
            $onProgress?->__invoke($lineCount, $results);
        }

        return new FilterResult(
            linesRead: $lineCount,
            patterns: $results,
            inputSource: $inputSource,
            command: $command,
            arguments: $arguments
        );
    }

    private function readLinesFromStream($stream): iterable
    {
        while (!feof(stream: $stream)) {
            yield fgets(stream: $stream);
        }
    }
}
