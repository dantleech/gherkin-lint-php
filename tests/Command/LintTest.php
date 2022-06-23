<?php

namespace DTL\GherkinLint\Tests\Command;

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
        $exitCode = $process->run();
        self::assertEquals(1, $exitCode);
        self::assertStringContainsString('1 problem', $process->getOutput());
    }
}
