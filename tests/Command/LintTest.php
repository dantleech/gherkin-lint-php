<?php

namespace DTL\BehatLint\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class LintTest extends TestCase
{
    public function testLintFile(): void
    {
        $process = new Process([
            'bin/behat-lint',
            'lint',
            'tests/Command/features',
        ], __DIR__ . '/../..');
        $process->mustRun();
    }
}
