<?php

namespace DTL\GherkinLint\Tests\Command;

class RulesTest extends CommandTestCase
{
    public function testRules(): void
    {
        $process = $this->lint(['rules']);
        self::assertExitCode(0, $process);
    }
}
