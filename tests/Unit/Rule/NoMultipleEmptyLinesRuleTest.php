<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoMultipleEmptyLinesRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoMultipleEmptyLinesRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoMultipleEmptyLinesRule();
    }

    public function provideTests(): Generator
    {
        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo


                        Scenario: Foo
                    EOT,
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Multiple empty lines are not allowed', $diagnostics->at(0)->message);
            }
        ];
    }
}
