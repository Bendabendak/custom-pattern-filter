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

class JsonFormat extends BaseFormatter
{
    public static function getName(): string
    {
        return 'json';
    }

    protected function generateContent(FilterResult $result): string
    {
        $json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return mb_convert_encoding(
            string: $json,
            to_encoding: 'UTF-8',
            from_encoding: 'UTF-8'
        );
    }

    public function writeToFile(string $filePath, FilterResult $result): void
    {
        $existing = [];

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $decoded = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $existing = $decoded;
            }
        }

        $existing[] = $result;

        $json = json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $json = mb_convert_encoding(
            string: $json,
            to_encoding: 'UTF-8',
            from_encoding: 'UTF-8'
        );

        file_put_contents($filePath, $json);
    }
}
