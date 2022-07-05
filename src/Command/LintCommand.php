<?php

namespace DTL\GherkinLint\Command;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\LintReport;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Report\TableReportRenderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LintCommand extends Command
{
    const ARG_PATH = 'path';

    public function __construct(
        private FeatureFinder $finder,
        private Linter $linter,
        private TableReportRenderer $reportRenderer
    ) {
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

        $start = microtime(true);

        $featureDiagnosticsList = [];
        foreach ($this->finder->find($path) as $featureFile) {
            $featureDiagnosticsList[] = new FeatureDiagnostics(
                $featureFile,
                iterator_to_array($this->linter->lint($featureFile->path, $featureFile->contents()), false)
            );
        }

        $elapsedTime = microtime(true) - $start;

        $report = new LintReport($featureDiagnosticsList, $elapsedTime);

        $this->reportRenderer->render($report);

        return $report->hasErrors() ? 1 : 0;
    }
}
