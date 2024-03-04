<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoConsecutiveEmptyLinesRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoConsecutiveEmptyLinesRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoConsecutiveEmptyLinesRule();
    }

    public static function provideTests(): Generator
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
                self::assertCount(0, $diagnostics);
            }
        ];

        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo

                        Scenario: Foo

                        Scenario: Foo

                        Scenario: Foo

                    EOT,
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];

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
                self::assertEquals('Consecutive empty lines are dissallowed', $diagnostics->at(0)->message);
            }
        ];

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
                self::assertEquals('Consecutive empty lines are dissallowed', $diagnostics->at(0)->message);
            }
        ];

        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo




                        Scenario: Foo


                    EOT,
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(2, $diagnostics);
                self::assertEquals('Consecutive empty lines are dissallowed', $diagnostics->at(0)->message);
            }
        ];

        yield [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo




                        Scenario: Foo


                        Scenario: Baz


                    EOT,
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(3, $diagnostics);
                self::assertEquals('Consecutive empty lines are dissallowed', $diagnostics->at(0)->message);
            }
        ];
    }
}
