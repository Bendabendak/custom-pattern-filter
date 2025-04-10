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
use CustomPatternFilter\FilterResult;
use Symfony\Component\Console\Output\OutputInterface;

class LogSummaryWriter
{
    public function __construct(
        private readonly ?string $summaryFile = null
    ) {
    }

    public function writeLogs(array $logFiles, FilterResult $result, FormatterFactory $formatterFactory, OutputInterface $output): void
    {
        $logged = [];

        foreach ($logFiles as $logFile) {
            $ext = strtolower(pathinfo($logFile, PATHINFO_EXTENSION));
            $absPath = realpath($logFile) ?: $logFile;
            $isNew = !file_exists($logFile);

            try {
                $logFormatter = $formatterFactory->get($ext);
                $logFormatter->writeToFile($logFile, $result);

                clearstatcache(true, $logFile);
                $fileSize = file_exists($logFile) ? filesize($logFile) : 0;
                $fileSizeReadable = $fileSize >= 1048576
                    ? round($fileSize / 1048576, 2) . ' MB'
                    : ($fileSize >= 1024
                        ? round($fileSize / 1024, 2) . ' KB'
                        : $fileSize . ' B');

                $logged[] = [
                    'path' => $logFile,
                    'format' => $ext,
                    'absolute' => $absPath,
                    'size' => $fileSizeReadable,
                    'mode' => $isNew ? 'created' : 'appended',
                ];
            } catch (\Throwable $e) {
                $output->writeln("<error>Could not log to '$logFile': {$e->getMessage()}</error>");
            }
        }

        if (!empty($logged)) {
            usort($logged, fn ($a, $b) => strcmp($a['path'], $b['path']));
            $output->writeln("\n<info>Results saved to:</info>");

            $summaryLines = [];

            foreach ($logged as $entry) {
                $line = sprintf(
                    " - %s (%s) [%s] - %s, %s",
                    $entry['path'],
                    $entry['format'],
                    $entry['mode'],
                    $entry['size'],
                    $entry['absolute']
                );

                $styled = sprintf(
                    " - <comment>%s</comment> (%s) [%s] - %s, %s",
                    $entry['path'],
                    $entry['format'],
                    $entry['mode'] === 'created' ? '<fg=green>created</>' : '<fg=yellow>appended</>',
                    $entry['size'],
                    $entry['absolute']
                );

                $summaryLines[] = $line;
                $output->writeln($styled);
            }

            $dir = 'log_summaries';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $summaryPath = $this->summaryFile
                ? $dir . '/' . basename($this->summaryFile)
                : $dir . '/log.summary.' . date('Y-m-d_His') . '.txt';

            file_put_contents($summaryPath, implode("\n", $summaryLines) . "\n", FILE_APPEND);
        }
    }
}
