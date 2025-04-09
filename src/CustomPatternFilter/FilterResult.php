<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

use JsonSerializable;

readonly class FilterResult implements JsonSerializable
{
    public function __construct(
        public int $linesRead,
        public array $patterns,
        public string $inputSource,
        public string $command,
        public array $arguments,
        public \DateTimeImmutable $timestamp = new \DateTimeImmutable()
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'lines_read' => $this->linesRead,
            'patterns' => $this->patterns,
            'input_source' => $this->inputSource,
            'command' => $this->command,
            'arguments' => $this->arguments,
            'timestamp' => $this->timestamp->format(DATE_ATOM),
        ];
    }
}
