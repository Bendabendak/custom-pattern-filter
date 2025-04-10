<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Formatter;

use CustomPatternFilter\FilterResult;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseFormatter implements FormatterInterface
{
    abstract public static function getName(): string;

    abstract protected function generateContent(FilterResult $result): string;

    public function print(OutputInterface $output, FilterResult $result): void
    {
        $output->write($this->generateContent($result) . "\n");
    }

    public function writeToFile(string $filePath, FilterResult $result): void
    {
        file_put_contents(
            filename: $filePath,
            data: $this->generateContent($result),
            flags: FILE_APPEND
        );
    }
}
