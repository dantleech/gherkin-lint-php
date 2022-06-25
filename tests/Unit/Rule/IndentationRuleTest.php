<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\IndentationRule;
use Generator;

class IndentationRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new IndentationRule();
    }

    public function provideTests(): Generator
    {
        yield 'feature at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'indentation' => [
                    'feature' => 0,
                ],
            ]

        ];
        yield 'feature at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                 Feature: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Expected indentation level on "Feature" to be 0 but got 1', $diagnostics->first()->message);

            },
        ];

        yield 'Rule at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                 Rule: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Expected indentation level on "Rule" to be 4 but got 1', $diagnostics->first()->message);

            },
        ];
        yield 'Rule at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Rule: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Background at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                 Background: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);

            },
        ];
        yield 'Background at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Background: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Scenario at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                 Scenario: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);

            },
        ];
        yield 'Scenario at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Scenario: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Step at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Scenario: Foobar
                     Given foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);

            },
        ];
        yield 'Step at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Scenario: Foobar
                        Given Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];

        yield 'Table at incorrect level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Scenario: Foobar
                        Given foobar I have
                         | foo | bar |

                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);

            },
        ];

        yield 'Table at correct level' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foo
                    Scenario: Foobar
                        Given Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];
    }
}
