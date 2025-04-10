<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter;

use CustomPatternFilter\Formatter\FormatterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class App extends Command
{
    public function __construct(private readonly ProcessorFactory $factory = new ProcessorFactory())
    {
        parent::__construct('filter');
    }

    protected function configure(): void
    {
        $this
            ->setName('filter')
            ->setDescription('Filter lines in a file using regex patterns')
            ->addArgument('file', InputArgument::REQUIRED, 'Input file path')
            ->addArgument('patterns', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Regex patterns to apply')
            ->addOption('debug', null, InputOption::VALUE_NONE, 'Enable simulated slow processing')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'Output format (json, txt, csv, md)')
            ->addOption('log', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Log results to one or more files')
            ->addOption('summary', null, InputOption::VALUE_REQUIRED, 'Optional summary log file name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $patterns = $input->getArgument('patterns');
        $file = $input->getArgument('file');
        $debug = $input->getOption('debug');
        $logFiles = $input->getOption('log') ?? [];
        $summaryFile = $input->getOption('summary');

        if (!file_exists(filename: $file)) {
            $output->writeln("<error>File '$file' not found.</error>");
            return Command::FAILURE;
        }

        $formatterFactory = new FormatterFactory();

        if ($input->getOption('format')) {
            $format = $input->getOption('format');
        } elseif (!empty($logFiles)) {
            $ext = strtolower(pathinfo(
                path: $logFiles[0],
                flags: PATHINFO_EXTENSION
            ));
            $available = array_map(
                callback: fn ($f): mixed => $f::getName(),
                array: include __DIR__ . '/Formatter/formatter_map.php'
            );
            $format = in_array(
                needle: $ext,
                haystack: $available,
                strict: true
            ) ? $ext : 'txt';
        } else {
            $format = 'txt';
        }

        $inputSource = realpath($file) ?? 'unknown';
        $commandName = $this->getName() ?? 'filter';
        $fullArgs = [
            'patterns' => $patterns,
            'debug' => $debug,
            'format' => $format,
        ];

        $onProgress = !$debug ? null : function ($lineCount, $results) use ($output): void {
            if ($lineCount % 10 === 0) {
                $output->write("\r\033[2K");
                $output->write("Lines read: $lineCount | ");
                foreach ($results as $pattern => $count) {
                    $output->write("<fg=cyan>$pattern</>: <fg=yellow>$count</>  ");
                }
            }
        };

        $stream = $this->factory->createStream($file, false);

        $result = $this->factory
            ->createProcessor()
            ->processStream(
                stream: $stream,
                patterns: $patterns,
                debug: $debug,
                onProgress: $onProgress,
                inputSource: $inputSource,
                command: $commandName,
                arguments: $fullArgs
            );

        fclose(stream: $stream);

        $formatter = $formatterFactory->get($format);
        $formatter->print(
            output: $output,
            result: $result
        );

        $logger = new LogSummaryWriter(summaryFile: $summaryFile);
        $logger->writeLogs(
            logFiles: $logFiles,
            result: $result,
            formatterFactory: $formatterFactory,
            output: $output
        );

        return Command::SUCCESS;
    }
}
