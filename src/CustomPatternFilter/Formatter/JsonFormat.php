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
use JsonSerializable;

class JsonFormat
{
    public function print(OutputInterface $output, FilterResult $result): void
    {
        $json = $this->encode($result);
        $output->writeln($json);
    }

    public function writeToFile(string $filePath, FilterResult $result): void
    {
        file_put_contents($filePath, $this->encode($result) . "\n", FILE_APPEND);
    }

    private function encode(JsonSerializable $result): string
    {
        $json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $json = preg_replace('/[[:cntrl:]&&[^\r\n\t]]/', '', $json);
        return mb_convert_encoding($json, 'UTF-8', 'UTF-8');
    }
}
