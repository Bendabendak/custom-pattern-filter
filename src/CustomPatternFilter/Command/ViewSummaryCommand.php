<?php

/**
 * @package   CustomPatternFilter
 * @author    Benda Martin <martinbendabendak@seznam.cz>
 * @license   MIT
 * @link      https://https://github.com/Bendabendak
 * @copyright Copyright (c) 2025 Benda Martin
 */

namespace CustomPatternFilter\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ViewSummaryCommand extends Command
{
    protected static $defaultName = 'summary:latest';

    protected function configure(): void
    {
        $this
            ->setName('summary:latest')
            ->setDescription('View the most recent summary file from the log_summaries directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $summaryDir = 'log_summaries';

        if (!is_dir($summaryDir)) {
            $output->writeln('<error>No log_summaries directory found.</error>');
            return Command::FAILURE;
        }

        $files = glob($summaryDir . '/log.summary.*.txt');

        if (empty($files)) {
            $output->writeln('<comment>No summary files found.</comment>');
            return Command::SUCCESS;
        }

        rsort($files);
        $latestFile = $files[0];

        $output->writeln("<info>Showing latest summary:</info> <comment>\$latestFile</comment>\n");
        $content = file_get_contents($latestFile);
        $output->writeln($content);

        return Command::SUCCESS;
    }
}
