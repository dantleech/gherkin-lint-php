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
        yield 'not pascal' => [
            '/home/daniel/foo/foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Filename "/home/daniel/foo/foo.feature" should be "PascalCase"', $diagnostics->first()->message);
            },
            [
                'file-name' => [
                    'style' => 'PascalCase',
                ]
            ]
        ];
        yield 'pascal 1' => [
            'Foo.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'file-name' => [
                    'style' => 'PascalCase',
                ]
            ]
        ];
        yield 'pascal 2' => [
            'FooBar.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'file-name' => [
                    'style' => 'PascalCase',
                ]
            ]
        ];
        yield 'pascal 3' => [
            'FooBar123.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            },
            [
                'file-name' => [
                    'style' => 'PascalCase',
                ]
            ]
        ];
        yield 'pascal 4' => [
            'FooBar_123.feature', '',
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            },
            [
                'file-name' => [
                    'style' => 'PascalCase',
                ]
            ]
        ];
    }
}
