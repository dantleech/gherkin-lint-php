<?php

namespace DTL\GherkinLint\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class LintTest extends TestCase
{
    public function testLintFile(): void
    {
        $process = new Process([
            'bin/gherkin-lint',
            'lint',
            'tests/Command/features',
        ], __DIR__ . '/../..');
        $exitCode = $process->run();
        if ($exitCode != 1) {
            self::fail($process->getErrorOutput());
            return;
        }
        $this->addToAssertionCount(1);
    }
}
