<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use Closure;
use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Model\RuleConfigFactory;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\FeatureFile;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleExample;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;
use PHPUnit\Framework\TestCase;

abstract class RuleTestCase extends TestCase
{
    abstract public function createRule(): Rule;

    /**
     * @return Generator<string,Closure(list<FeatureDiagnostic>):void>
     */
    abstract public function provideTests(): Generator;

    /**
     * @dataProvider provideExamples
     */
    public function testExamples(RuleExample $example): void
    {
        $rule = $this->createRule();
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
        $rule = $this->createRule();
        foreach ($rule->describe()->examples as $example) {
            yield [$example];
        }
    }

    /**
     * @dataProvider provideTests
     */
    public function testRule(TestFeature|array $features, Closure $assertion, array $config = []): void
    {
        if ($features instanceof TestFeature) {
            $features = [$features];
        }

        $rule = $this->createRule();
        $linter = new Linter(
            new GherkinParser(),
            [$rule],
            new RuleConfigFactory(
                ConfigMapper::create(),
                [
                    $rule->describe()->name => new ConfigRule(true, $config)
                ]
            ),
        );
        foreach ($features as $feature) {
            $diagnostics = iterator_to_array($linter->lint($feature->path, $feature->content));
        }
        $assertion(new FeatureDiagnostics(new FeatureFile('/foo', '/bar'), $diagnostics));
    }
}
