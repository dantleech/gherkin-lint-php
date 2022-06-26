<?php

namespace DTL\GherkinLint\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class LintTest extends CommandTestCase
{
    public function testLintFile(): void
    {
        $process = $this->lint(['lint', 'tests/Command/features']);

        $expected = 1;
        $this->assertExitCode(1, $process);
    }
}
