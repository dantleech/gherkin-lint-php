<?php

namespace DTL\GherkinLint\Tests\RuleExample;

use DTL\GherkinLint\GherkinLintContainer;
use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Model\Rule;
use Generator;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\RuleConfigFactory;
use Cucumber\Gherkin\GherkinParser;

use DTL\GherkinLint\Model\RuleExample;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class RuleExampleTest extends TestCase
{
    /**
     * @dataProvider provideExamples
     */
    public function testExamples(Rule $rule, RuleExample $example): void
    {
        $linter = new Linter(
            new GherkinParser(),
            [
                $rule
            ],
            new RuleConfigFactory(
                ConfigMapper::create(),
                [
                    $rule->describe()->name => new ConfigRule(
                        true,
                        // convert the config object into an array
                        json_decode(json_encode($example->config ?? []), true)
                    )
                ]
            ),
        );
        $diagnostics = iterator_to_array($linter->lint($example->filename ?? 'test.feature', $example->example), false);
        if ($example->valid && count($diagnostics) > 0) {
            $this->fail(sprintf('Expected example "%s" of rule "%s" to be valid but it failed', $example->title, $rule->describe()->name));
            return;
        }

        if (false === $example->valid && count($diagnostics) === 0) {
            $this->fail(sprintf('Expected example "%s" of rule "%s" to not be valid but it was OK', $example->title, $rule->describe()->name));
            return;
        }

        $this->addToAssertionCount(1);
    }

    /**
     * @return Generator<RuleExample>
     */
    public function provideExamples(): Generator
    {
        $container = new GherkinLintContainer(new BufferedOutput(), new Config([]));

        foreach ($container->createRules()->rules() as $rule) {
            foreach ($rule->describe()->examples as $example) {
                yield [$rule, $example];
            }
        }
    }
}
