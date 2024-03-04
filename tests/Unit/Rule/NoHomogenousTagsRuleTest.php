<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoHomogenousTagsRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoHomogenousTagsRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoHomogenousTagsRule();
    }

    public static function provideTests(): Generator
    {
        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foobar
                        @foo
                        Scenario: Foo
                            When this then that
                        @foo
                        Scenario: Bar
                            When this then that
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(2, $diagnostics);
                self::assertEquals('Tag "@foo" is present on each Scenario, move to Feature', $diagnostics->at(0)->message);
                self::assertEquals('Tag "@foo" is present on each Scenario, move to Feature', $diagnostics->at(1)->message);
            }
        ];
        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foobar
                        @two
                        Scenario: Foo
                            When this then that
                        @foo
                        Scenario: Bar
                            When this then that
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];
    }
}
