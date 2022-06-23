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
        $path = (string)$input->getArgument(self::ARG_PATH);
        $files = $this->finder->find($path);

        $table = new Table($output);
        $table->setHeaders([
            'line', 'col', 'severity', 'message',
        ]);

        foreach ($files as $fileInfo) {
            $diagnostics = $this->linter->lint($fileInfo->path, file_get_contents($fileInfo->path));
            $output->writeln($fileInfo->relativePath);
            foreach ($diagnostics as $diagnostic) {
                $table->addRow([
                    $diagnostic->position->lineNo,
                    $diagnostic->position->colNo,
                    $diagnostic->severity,
                    $diagnostic->message
                ]);
            }
            $table->render();
            $output->writeln('');
        }

        return 0;
    }
}
