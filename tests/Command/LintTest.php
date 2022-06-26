<?php

namespace DTL\GherkinLint\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class LintTest extends TestCase
{
    public function testLintFile(): void
    {
        $process = new Process([
            'bin/gherkinlint',
            'lint',
            'tests/Command/features',
        ], __DIR__ . '/../..');
        $exitCode = $process->run();

        $expected = 1;
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
