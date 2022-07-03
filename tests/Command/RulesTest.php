<?php

namespace DTL\GherkinLint\Tests\Command;

class RulesTest extends CommandTestCase
{
    public function testRules(): void
    {
        $process = $this->lint(['rules']);
        self::assertExitCode(0, $process);
    }

    public function testInvalidRules(): void
    {
        $this->workspace()->putContents('gherkinlint.json', json_encode([
            'rules' => [
                'allowed-tags' => [
                    'allow' => 123,
                ],
            ],
        ]));
        $process = $this->lint(['rules']);
        self::assertExitCode(1, $process);
        self::assertStringContainsString('Argument "allow" has type "array"', $process->getErrorOutput());
    }
}
