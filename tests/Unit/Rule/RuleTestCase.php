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
     * @dataProvider provideTests
     */
    public function testRule(string $path, string $contents, Closure $assertion, array $config = []): void
    {
        $rule = $this->createRule();
        $assertion(new FeatureDiagnostics(new FeatureFile('/foo', '/bar'), iterator_to_array((
            new Linter(
                new GherkinParser(
                    includePickles: false,
                    includeSource: false,
                    includeGherkinDocument: true,
                ),
                [$rule],
                new RuleConfigFactory(
                    ConfigMapper::create(),
                    [
                        $rule->describe()->name => new ConfigRule(true, $config)
                    ]
                ),
            )
        )->lint($path, $contents))));
    }
}
