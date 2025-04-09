<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CustomPatternFilter\OutputFormat;
use CustomPatternFilter\Formatter\JsonFormat;
use CustomPatternFilter\Formatter\TextFormat;

class App extends Command
{
    protected static $defaultName = 'filter';

    public function __construct(private ProcessorFactory $factory = new ProcessorFactory())
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Filters a file with regex patterns')
            ->setName('filter')
            ->addArgument('file', InputArgument::REQUIRED, 'File to scan')
            ->addArgument('patterns', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Regex patterns')
            ->addOption('debug', null, InputOption::VALUE_NONE, 'Simulate slow processing')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Output format: json', 'text')
            ->addOption('log', null, InputOption::VALUE_REQUIRED, 'Log results to file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $patterns = $input->getArgument('patterns');
        $debug = $input->getOption('debug');
        $logFile = $input->getOption('log');
        $formatOption = strtolower($input->getOption('format'));
        try {
            $format = OutputFormat::from($formatOption);
        } catch (\ValueError $e) {
            $output->writeln("<error>Invalid format '$formatOption'. Only 'json' is supported for now.</error>");
            return Command::FAILURE;
        }

        $asText = $format === OutputFormat::TEXT;

        if (!$file) {
            $output->writeln('<error>Provide a file</error>');
            return Command::FAILURE;
        }

        try {
            $stream = $this->factory->createStream($file);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        $onProgress = !$asText ? function ($lineCount, $results) use ($output, $debug): void {
            if ($lineCount % 500 === 0 || $debug) {
                $output->write("\r\033[2K");
                $output->write("Lines read: $lineCount | ");
                foreach ($results as $pattern => $count) {
                    $output->write("<fg=cyan>$pattern</>: <fg=yellow>$count</>  ");
                }
            }
        } : null;

        $inputSource = realpath($file) ?? 'unknown';

        $processor = $this->factory->createProcessor();
        $commandName = $this->getName() ?? 'unknown';
        $fullArgs = [
            'patterns' => $patterns,
            'debug' => $debug,
            'format' => $format->value,
        ];

        $result = $processor->processStream(
            stream: $stream,
            patterns: $patterns,
            debug: $debug,
            onProgress: $onProgress,
            inputSource: $inputSource,
            command: $commandName,
            arguments: $fullArgs
        );

        fclose(stream: $stream);
        if (!$asText) {
            $output->write("\r\033[2K");
        }

        $formatter = match ($format) {
            OutputFormat::JSON => new JsonFormat(),
            default => new TextFormat(),
        };

        $formatter->print(
            output: $output,
            result: $result
        );

        if ($logFile) {
            $formatter->writeToFile(
                filePath: $logFile,
                result: $result
            );
        }

        return Command::SUCCESS;
    }
}
