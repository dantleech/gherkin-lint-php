<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDisallowedPatternsConfig;
use DTL\GherkinLint\Rule\NoDisallowedPatternsRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;
use PHPUnit\Framework\TestCase;

class NoDisallowedPatternsRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDisallowedPatternsRule();
    }

    public function provideTests(): Generator
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
            function (FeatureDiagnostics $diagnostics) {
                self::assertCount(0, $diagnostics);
            },
            [
                'patterns' => [
                    '/Client/u',
                ],
            ]
        ];
    }
}
