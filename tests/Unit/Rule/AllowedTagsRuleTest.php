<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\AllowedTagsRule;
use Generator;

class AllowedTagsRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new AllowedTagsRule();
    }

    public function provideTests(): Generator
    {
        yield 'allowed feature tags' => [
            'foo.feature',
            <<<'EOT'
                @foo @foo
                Feature: Foobar
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'allow' => ['@foo'],
            ]
        ];

        yield 'disallowed feature tags' => [
            'foo.feature',
            <<<'EOT'
                @foo @foo @bar
                Feature: Foobar
                    Scenario: Foo
                        When this then that
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Tag "@bar" is not allowed', $diagnostics->first()->message);
            },
            [
                'allow' => ['@foo'],
            ]
        ];

        yield 'disallowed on scenario feature tags' => [
            'foo.feature',
            <<<'EOT'
                Feature: Foobar
                    @foo @foo @bar
                    Scenario: Foo
                        When this then that
                EOT
            ,
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Tag "@bar" is not allowed', $diagnostics->first()->message);
            },
            [
                'allow' => ['@foo'],
            ]
        ];
    }
}
