<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\FileNameRule;
use Generator;

class FileNameRuleTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new FileNameRule();
    }

    public function provideTests(): Generator
    {
        yield 'pascal fail' => [
            '/home/daniel/foo/foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Filename "/home/daniel/foo/foo.feature" should be "PascalCase"', $diagnostics->first()->message);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 1' => [
            'Foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 2' => [
            'FooBar.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 3' => [
            'FooBar123.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 4 fail' => [
            'FooBar_123.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'pascal 7' => [
            'FooBarBarBoo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'PascalCase',
            ]
        ];
        yield 'camel case fail' => [
            'FooBar_123.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 1' => [
            'foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 2' => [
            'fooBar.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'camel case 3' => [
            'fooBarBaz.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'camelCase',
            ]
        ];
        yield 'snake case fail' => [
            'fooBarBaz.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'snake case 1' => [
            'foo_bar_baz.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'snake case 2' => [
            'foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'snake_case',
            ]
        ];
        yield 'kebab case fail' => [
            'fooFoo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
        yield 'kebab case 1' => [
            'foo-boo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
        yield 'kebab case 2' => [
            'foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'style' => 'kebab-case',
            ]
        ];
    }
}
