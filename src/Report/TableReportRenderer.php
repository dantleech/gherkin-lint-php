<?php

namespace DTL\GherkinLint\Report;

use DTL\GherkinLint\Model\LintReport;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

final class TableReportRenderer
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function render(LintReport $report): void
    {
        foreach ($report as $featureDiagnostics) {
            if (!count($featureDiagnostics)) {
                continue;
            }
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

        $this->summarise($report->errorCount(), count($report), $report->elapsedTime);
    }

    private function summarise(int $errorCount, int $nbFeatureFiles, float $elapsedTime): void
    {
        if ($errorCount) {
            $this->output->writeln(
                sprintf(
                    '<error>%s problems found in %d feature files (took %s seconds)</>',
                    $errorCount,
                    $nbFeatureFiles,
                    number_format($elapsedTime, 4)
                )
            );
            return;
        }

        $this->output->writeln(
            sprintf(
                'No problems found in %d feature files (took %s seconds)</>',
                $nbFeatureFiles,
                $elapsedTime
            )
        );
    }
}
