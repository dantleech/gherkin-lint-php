<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoEmptyFileRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoEmptyFileRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoEmptyFileRule();
    }

    public static function provideTests(): Generator
    {
        yield 'empty file' => [
            new TestFeature(
                'foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals(
                    'Feature file is not allowed to be empty',
                    $diagnostics->first()->message
                );
            }
        ];

        yield 'non-empty file' => [
            new TestFeature(
                'foo.feature',
                'Feature: Foobar'
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];
    }
}
