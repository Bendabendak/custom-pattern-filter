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

interface FormatterInterface
{
    public static function getName(): string;

    public function print(OutputInterface $output, FilterResult $result): void;

    public function writeToFile(string $filePath, FilterResult $result): void;
}
