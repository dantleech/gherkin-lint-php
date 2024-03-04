<?php

namespace DTL\GherkinLint\Tests\RuleExample;

use DTL\GherkinLint\GherkinLintContainer;
use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\FeatureFile;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Model\Rule;
use Generator;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\RuleConfigFactory;

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
        $linter = Linter::create(
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
            [
                $rule
            ],
        );
        $diagnostics = new FeatureDiagnostics(new FeatureFile('file.feature', ''), iterator_to_array($linter->lint($example->filename ?? 'test.feature', $example->example), false));

        if ($example->valid && count($diagnostics) > 0) {
            $this->fail(sprintf(
                'Expected example "%s" of rule "%s" to be valid but it failed at L%d:%d => L%d:%s: %s',
                $example->title,
                $rule->describe()->name,
                $diagnostics->at(0)->range->start->lineNo,
                $diagnostics->at(0)->range->start->colNo,
                $diagnostics->at(0)->range->end->lineNo,
                $diagnostics->at(0)->range->end->colNo,
                $diagnostics->at(0)->message
            ));
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
    public static function provideExamples(): Generator
    {
        $container = new GherkinLintContainer(new BufferedOutput(), new Config([]));

        foreach ($container->createRules()->rules() as $rule) {
            foreach ($rule->describe()->examples as $example) {
                yield [$rule, $example];
            }
        }
    }
}
