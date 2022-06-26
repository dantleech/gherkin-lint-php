<?php

namespace DTL\GherkinLint\Tests\Command;

class LintTest extends CommandTestCase
{
    public function testLintFile(): void
    {
        $process = $this->lint(['lint', 'tests/Command/features']);

        $expected = 1;
        $this->assertExitCode(1, $process);
    }
}
