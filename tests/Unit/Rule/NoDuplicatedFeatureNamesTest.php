<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDuplicatedFeatureNames;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoDuplicatedFeatureNamesTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDuplicatedFeatureNames();
    }

    public static function provideTests(): Generator
    {
        yield 'no duplicated feature' => [
            [
                new TestFeature(
                    'foo.feature',
                    <<<'EOT'
                        Feature: Foobar
                        EOT
                ),
                new TestFeature(
                    'barfoo.feature',
                    <<<'EOT'
                        Feature: Barfoo
                        EOT
                ),
            ],
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
            ]
        ];

        yield 'duplicated feature' => [
            [
                new TestFeature(
                    'foo.feature',
                    <<<'EOT'
                        Feature: Foobar
                        EOT
                ),
                new TestFeature(
                    'barfoo.feature',
                    <<<'EOT'
                        Feature: Foobar
                        EOT
                ),
            ],
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Feature already defined in "foo.feature"', $diagnostics->first()->message);
            },
            [
            ]
        ];
    }
}
