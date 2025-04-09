<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

class ProcessorFactory
{
    public function createStream(string $filePath): mixed
    {
        if ($filePath) {
            $stream = fopen(
                filename: $filePath,
                mode: 'r'
            );
        } else {
            throw new \InvalidArgumentException('No input source specified.');
        }

        if (!$stream) {
            throw new \RuntimeException('Could not open input stream.');
        }

        return $stream;
    }

    public function createProcessor(): StreamProcessor
    {
        return new StreamProcessor();
    }
}
