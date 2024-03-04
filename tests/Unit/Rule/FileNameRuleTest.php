<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\FileNameRule;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class FileNameRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new FileNameRule();
    }

    public static function provideTests(): Generator
    {
        yield 'pascal fail' => [
            new TestFeature(
                '/home/daniel/foo/foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Filename "/home/daniel/foo/foo.feature" should be "PascalCase"', $diagnostics->first()->message);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 1' => [
            new TestFeature(
                'Foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 2' => [
            new TestFeature(
                'FooBar.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 3' => [
            new TestFeature(
                'FooBar123.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 4 fail' => [
            new TestFeature(
                'FooBar_123.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 7' => [
            new TestFeature(
                'FooBarBarBoo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'camel case fail' => [
            new TestFeature(
                'FooBar_123.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 1' => [
            new TestFeature(
                'foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 2' => [
            new TestFeature(
                'fooBar.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 3' => [
            new TestFeature(
                'fooBarBaz.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'snake case fail' => [
            new TestFeature(
                'fooBarBaz.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'snake case 1' => [
            new TestFeature(
                'foo_bar_baz.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'snake case 2' => [
            new TestFeature(
                'foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'kebab case fail' => [
            new TestFeature(
                'fooFoo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
        yield 'kebab case 1' => [
            new TestFeature(
                'foo-boo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
        yield 'kebab case 2' => [
            new TestFeature(
                'foo.feature',
                ''
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
    }
}
