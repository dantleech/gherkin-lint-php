<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoEmptyFileRule;
use Generator;

class NoEmptyFileRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoEmptyFileRule();
    }

    public function provideTests(): Generator
    {
        yield 'empty file' => [
            '',
            function (FeatureDiagnostics $diagnostics) {
                self::assertCount(1, $diagnostics);
                self::assertEquals(
                    'Feature file is not allowed to be empty',
                    $diagnostics->first()->message
                );
            }
        ];

        yield 'non-empty file' => [
            'Feature: Foobar',
            function (FeatureDiagnostics $diagnostics) {
                self::assertCount(0, $diagnostics);
            }
        ];
    }
}
