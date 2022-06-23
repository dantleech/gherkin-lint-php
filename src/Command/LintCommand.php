<?php

namespace DTL\GherkinLint\Command;

use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\Linter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LintCommand extends Command
{
    const ARG_PATH = 'path';

    public function __construct(private FeatureFinder $finder, private Linter $linter)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('lint');
        $this->setDescription('Lint feature files');
        $this->addArgument(self::ARG_PATH, InputArgument::REQUIRED, 'Path to feature files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);
        $path = (string)$input->getArgument(self::ARG_PATH);
        $files = $this->finder->find($path);

        $table = new Table($output);
        $table->setHeaders([
            'line', 'col', 'severity', 'message',
        ]);
        $errorCount = 0;

        foreach ($files as $fileInfo) {
            $diagnostics = iterator_to_array(
                $this->linter->lint(
                    $fileInfo->path,
                    file_get_contents($fileInfo->path)
                )
            );

            if (count($diagnostics) === 0) {
                continue;
            }

            $errorCount += count($diagnostics);

            $output->writeln($fileInfo->relativePath);

            foreach ($diagnostics as $diagnostic) {
                $table->addRow([
                    $diagnostic->range->start->lineNo,
                    $diagnostic->range->end->colNo,
                    $diagnostic->severity->toString(),
                    $diagnostic->message
                ]);
            }

            $table->render();
            $output->writeln('');
        }

        $elapsedTime = number_format(microtime(true) - $start, 2);

        if ($errorCount) {
            $output->writeln(
                sprintf(
                    '<error>%s problems found in %s seconds</>',
                    $errorCount,
                    $elapsedTime,
                )
            );

            return 1;
        }

        $output->writeln(
            sprintf(
                'No problems found in %s seconds</>',
                $elapsedTime,
            )
        );

        return 0;
    }
}
