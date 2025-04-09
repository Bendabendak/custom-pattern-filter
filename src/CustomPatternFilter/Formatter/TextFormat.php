<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Formatter;

use Symfony\Component\Console\Output\OutputInterface;
use CustomPatternFilter\FilterResult;

class TextFormat
{
    public function print(OutputInterface $output, FilterResult $result): void
    {
        $output->writeln("<info>Finished processing {$result->linesRead} lines.</info>");
        $output->writeln("<info>Pattern match counts:</info>");
        foreach ($result->patterns as $pattern => $count) {
            $output->writeln("  <info>$pattern</info>: <comment>$count</comment>");
        }
    }

    public function writeToFile(string $filePath, FilterResult $result): void
    {
        $lines = [
            "Lines read: {$result->linesRead}",
            "Pattern matches:",
        ];

        foreach ($result->patterns as $pattern => $count) {
            $lines[] = "  $pattern: $count";
        }

        file_put_contents($filePath, implode("\n", $lines) . "\n", FILE_APPEND);
    }
}
