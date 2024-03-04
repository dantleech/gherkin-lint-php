<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\IndentationRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class IndentationRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new IndentationRule();
    }

    public static function provideTests(): Generator
    {
        yield 'feature at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'feature' => 0,
            ]

        ];
        yield 'feature at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                     Feature: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Expected indentation level on "Feature" to be 0 but got 1', $diagnostics->first()->message);
            },
        ];

        yield 'Rule at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                     Rule: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Expected indentation level on "Rule" to be 4 but got 1', $diagnostics->first()->message);
            },
        ];
        yield 'Rule at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Rule: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Background at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                     Background: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];
        yield 'Background at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Background: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Scenario at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                     Scenario: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];
        yield 'Scenario at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Step at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                         Given foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];
        yield 'Step at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Table at incorrect level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have
                             | foo | bar |

                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];

        yield 'Table at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given Foobar
                                | foo | bar |
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Table row uneven' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given Foobar
                                | foo | bar |
                                 | foo | bar |
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];

        yield 'Literal block at wrong level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have:
                              """
                              FOO
                              """

                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];

        yield 'Literal block at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have:
                            """
                            FOO
                            """

                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Examples at wrong level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have <asd>
                             Examples:
                                | asd | asd |
                                | 12  | 123 |
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];

        yield 'Examples block at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have <asd>
                            Examples:
                                | asd | asd |
                                | 12  | 123 |
                            """

                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Examples table at wrong level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have <asd>
                            Examples:
                                 | asd | asd |
                                 | 12  | 123 |
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
        ];

        yield 'Examples table block at correct level' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foo
                        Scenario: Foobar
                            Given foobar I have <asd>
                            Examples:
                                | asd | asd |
                                | 12  | 123 |
                            """

                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];
    }
}
