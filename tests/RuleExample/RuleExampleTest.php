<?php

namespace DTL\GherkinLint\Tests\RuleExample;

use DTL\GherkinLint\GherkinLintContainer;
use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleExample;
use DTL\GherkinLint\Tests\Util\Workspace;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Process\Process;

class RuleExampleTest extends TestCase
{
    /**
     * @dataProvider provideRules
     */
    public function testRuleExamples(Rule $rule, RuleExample $example): void
    {
        $this->workspace()->reset();

        $this->workspace()->putContents('features/' . $example->filename, $example->example);
        $this->workspace()->putContents('gherkinlint.json', json_encode([
            'rules' => [
                $rule->describe()->name => $example->config ?? []
            ],
        ]));

        $process = new Process([
            __DIR__ . '/../../bin/gherkinlint',
            'lint',
            'features',
        ], $this->workspace()->path('/'));

        if ($example->valid) {
            $process->mustRun();
            $this->addToAssertionCount(1);
            return;
        }
        
        $process->run();

        self::assertEquals(
            1,
            $process->getExitCode(),
            sprintf(
                "Expected example to fail, but it was reported to be OK: \nOUT:\n%s \nERR:\n%s",
                $process->getOutput(),
                $process->getErrorOutput()
            )
        );
    }

    /**
     * @return Generator<string,array{Rule,RuleExample}>
     */
    public function provideRules(): Generator
    {
        foreach ($this->createContainer()->createRules()->rules() as $rule) {
            foreach ($rule->describe()->examples as $index => $example) {
                yield sprintf('%s #%s', $rule->describe()->name, $index) => [
                    $rule,
                    $example
                ];
            }
        }
    }

    private function createContainer(): GherkinLintContainer
    {
        $output = new BufferedOutput();
        return new GherkinLintContainer($output, new Config(), false);
    }

    private function workspace(): Workspace
    {
        return new Workspace(__DIR__ . '/Workspace');
    }
}
