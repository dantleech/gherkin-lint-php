<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use Closure;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Model\RuleConfigFactory;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\FeatureFile;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;
use PHPUnit\Framework\TestCase;

abstract class RuleTestCase extends TestCase
{
    abstract public function createRule(): Rule;

    /**
     * @return Generator<string,Closure(list<FeatureDiagnostic>):void>
     */
    abstract public static function provideTests(): Generator;

    /**
     * @dataProvider provideTests
     */
    public function testRule(TestFeature|array $features, Closure $assertion, array $config = []): void
    {
        if ($features instanceof TestFeature) {
            $features = [$features];
        }

        $rule = $this->createRule();
        $linter = Linter::create(
            new RuleConfigFactory(
                ConfigMapper::create(),
                [
                    $rule->describe()->name => new ConfigRule(true, $config)
                ]
            ),
            [$rule],
        );
        foreach ($features as $feature) {
            $diagnostics = iterator_to_array($linter->lint($feature->path, $feature->content));
        }
        $assertion(new FeatureDiagnostics(new FeatureFile('/foo', '/bar'), $diagnostics));
    }
}
