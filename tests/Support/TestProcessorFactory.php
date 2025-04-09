<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace Tests\Support;

use CustomPatternFilter\ProcessorFactory;
use CustomPatternFilter\StreamProcessor;

class TestProcessorFactory extends ProcessorFactory
{
    private $mockStream;
    private ?StreamProcessor $mockProcessor = null;

    public function setMockStream($stream): void
    {
        $this->mockStream = $stream;
    }

    public function setMockProcessor(StreamProcessor $processor): void
    {
        $this->mockProcessor = $processor;
    }

    public function createStream(string $filePath): mixed
    {
        return $this->mockStream ?? parent::createStream(filePath: $filePath);
    }

    public function createProcessor(): StreamProcessor
    {
        return $this->mockProcessor ?? parent::createProcessor();
    }
}
