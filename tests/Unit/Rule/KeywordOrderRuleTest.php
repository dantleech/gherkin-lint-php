<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\KeywordOrderRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class KeywordOrderRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new KeywordOrderRule();
    }

    public static function provideTests(): Generator
    {
        yield 'correct order' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            Given I do this
                            And I do that
                            When I pressed play
                            But this happened
                            And I didn't expect it to happen
                            Then something should have happened
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];
        yield 'starts with wrong keyword' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            Then this happened
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('First step must start with "Given" or "When", got "Then"', $diagnostics->first()->message);
            },
        ];
        yield 'starts with wrong keyword 2' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            And this happened
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('First step must start with "Given" or "When", got "And"', $diagnostics->first()->message);
            },
        ];
        yield 'Extra when' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            When I do this
                            And this happened
                            Then this should happen
                            When I do this
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Keyword "When" cannot come after a "Then"', $diagnostics->first()->message);
            },
        ];

        yield 'With tolerate Then before When' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            Given I did something
                            Then an exception should be thrown
                            When I do this
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'tolerateThenBeforeWhen' => true,
            ]
        ];

        yield 'no errors on invalid keyword (rule is not responsible for this)' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Video Player

                        Scenario: Pressing Play
                            AAA I did something
                            BBB I did something
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];
    }
}
