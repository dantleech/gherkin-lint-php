<?php

namespace DTL\GherkinLint\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CommandTestCase extends TestCase
{
    protected function lint(array $command): Process
    {
        $process = new Process([
            'bin/gherkinlint',
            ... $command
        ], __DIR__ . '/../..');
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
