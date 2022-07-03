<?php

namespace DTL\GherkinLint\Tests\Command;

use DTL\GherkinLint\Tests\LintTestCase;
use Symfony\Component\Process\Process;

class CommandTestCase extends LintTestCase
{
    protected function lint(array $command): Process
    {
        $process = new Process([
            __DIR__ . '/../../bin/gherkinlint',
            ... $command
        ], $this->workspace()->path('/'));
        $process->run();
        return $process;
    }

    protected function assertExitCode(int $expected, Process $process): void
    {
        $exitCode = $process->getExitCode();
        if ($exitCode != $expected) {
            self::fail(sprintf(
                'Process should have exited with code "%s" but got "%s": %s %s',
                $expected,
                $process->getExitCode(),
                $process->getOutput(),
                $process->getErrorOutput()
            ));
            return;
        }
        
        $this->addToAssertionCount(1);
    }
}
