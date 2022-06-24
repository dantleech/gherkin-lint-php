<?php

namespace DTL\GherkinLint\Report;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\LintReport;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class TableReport
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function render(LintReport $report): void
    {
        foreach ($report as $featureDiagnostics) {
            $this->output->writeln($featureDiagnostics->file->relativePath);
            $table = new Table($this->output);
            $table->setHeaders([
                'line', 'col', 'severity', 'message',
            ]);

            foreach ($featureDiagnostics as $diagnostic) {
                $table->addRow([
                    $diagnostic->range->start->lineNo,
                    $diagnostic->range->end->colNo,
                    $diagnostic->severity->toString(),
                    $diagnostic->message
                ]);
            }

            $table->render();
            $this->output->writeln('');
        }
    }
}
