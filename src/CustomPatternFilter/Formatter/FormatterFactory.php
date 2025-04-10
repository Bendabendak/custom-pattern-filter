<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Formatter;

use RuntimeException;

class FormatterFactory
{
    /** @var array<string, FormatterInterface> */
    private array $formatters = [];

    public function __construct()
    {
        $map = include __DIR__ . '/formatter_map.php';

        foreach ($map as $formatterClass) {
            if (!class_exists($formatterClass)) {
                continue;
            }

            $formatter = new $formatterClass();

            if (!method_exists(
                object_or_class: $formatter,
                method: 'getName'
            )) {
                throw new RuntimeException("Formatter class $formatterClass must have a static getName() method.");
            }

            if (!$formatter instanceof FormatterInterface) {
                throw new RuntimeException("Formatter class $formatterClass must implement FormatterInterface.");
            }

            $this->formatters[strtolower($formatterClass::getName())] = $formatter;
        }
    }

    public function get(string $formatName): FormatterInterface
    {
        $formatName = strtolower($formatName);

        if (!isset($this->formatters[$formatName])) {
            throw new RuntimeException("Formatter '$formatName' not found.");
        }

        return $this->formatters[$formatName];
    }
}
