<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\AllowedTagsRule;
use DTL\GherkinLint\Rule\KeywordOrderRule;
use Generator;

class KeywordOrderRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new KeywordOrderRule();
    }

    public function provideTests(): Generator
    {
        yield 'correct order' => [
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
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
        ];
        yield 'starts with wrong keyword' => [
            'foo.feature',
            <<<'EOT'
                Feature: Video Player

                    Scenario: Pressing Play
                        Then this happened
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('First step must start with "Given" or "When", got "Then"', $diagnostics->first()->message);
            },
        ];
        yield 'starts with wrong keyword 2' => [
            'foo.feature',
            <<<'EOT'
                Feature: Video Player

                    Scenario: Pressing Play
                        And this happened
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('First step must start with "Given" or "When", got "And"', $diagnostics->first()->message);
            },
        ];
        yield 'Extra when' => [
            'foo.feature',
            <<<'EOT'
                Feature: Video Player

                    Scenario: Pressing Play
                        When I do this
                        And this happened
                        Then this should happen
                        When I do this
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Keyword "When" cannot come after a "Then"', $diagnostics->first()->message);
            },
        ];
    }
}
