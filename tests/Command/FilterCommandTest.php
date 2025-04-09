<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use CustomPatternFilter\App;
use CustomPatternFilter\StreamProcessor;
use Tests\Support\TestProcessorFactory;

class FilterCommandTest extends TestCase
{
    private CommandTester $tester;

    protected function setUp(): void
    {
        $stream = fopen(
            filename: 'php://memory',
            mode: 'r+'
        );
        fwrite(
            stream: $stream,
            data: "john@example.com\nbob@gmail.com\n"
        );
        rewind(stream: $stream);

        $factory = new TestProcessorFactory();
        $factory->setMockStream(stream: $stream);
        $factory->setMockProcessor(processor: new StreamProcessor());

        $app = new Application();
        $app->add(new App(factory: $factory));
        $command = $app->find('filter');

        $this->tester = new CommandTester($command);
    }

    public function testJsonOutputWithInjectedFactory(): void
    {
        $this->tester->execute([
            'file' => 'tests/test-data/users.txt',
            'patterns' => ['/@gmail\.com/', '/^john/'],
            '--format' => 'json'
        ], [
            'decorated' => false,
        ]);

        $output = $this->tester->getDisplay();
        $this->assertJson($output);

        $result = json_decode(
            json: $output,
            associative: true
        );
        $this->assertEquals(3, $result['lines_read']);
        $this->assertEquals(1, $result['patterns']['/@gmail\.com/']);
        $this->assertEquals(1, $result['patterns']['/^john/']);
    }
}
