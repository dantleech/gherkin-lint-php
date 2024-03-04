<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDisallowedPatternsRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoDisallowedPatternsRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDisallowedPatternsRule();
    }

    public static function provideTests(): Generator
    {
        yield [
            new TestFeature(
                'test.feature',
                <<<'EOT'
                    Feature: Foobar
                        Scenario: Barfoo
                           Given this
                           When I do that
                           Then something should happen
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'patterns' => [
                    '/Client/u',
                ],
            ]
        ];

        yield [
            new TestFeature(
                'test.feature',
                <<<'EOT'
                    Feature: Foobar
                        Scenario: Barfoo
                           Given this
                           When I do that
                           Then something should happen
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals(5, $diagnostics->at(0)->range->start->lineNo);
                self::assertEquals(12, $diagnostics->at(0)->range->start->colNo);
                self::assertEquals(5, $diagnostics->at(0)->range->end->lineNo);
                self::assertEquals(21, $diagnostics->at(0)->range->end->colNo);
            },
            [
                'patterns' => [
                    '/something/u',
                ],
            ]
        ];

        yield 'multiple' => [
            new TestFeature(
                'test.feature',
                <<<'EOT'
                    Feature: Foobar
                        Scenario: Barfoo
                           Given this
                           When I do that
                           Then something should pass

                        Scenario: Something is up
                           Then something should happen
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(4, $diagnostics);
                self::assertEquals(
                    'Text "something" is not allowed by pattern "/something/i"',
                    $diagnostics->at(0)->message
                );
                self::assertEquals(
                    'Text "Something" is not allowed by pattern "/something/i"',
                    $diagnostics->at(1)->message
                );
                self::assertEquals(
                    'Text "happen" is not allowed by pattern "/happen/i"',
                    $diagnostics->at(3)->message
                );
            },
            [
                'patterns' => [
                    '/something/i',
                    '/happen/i',
                ],
            ]
        ];
    }
}
