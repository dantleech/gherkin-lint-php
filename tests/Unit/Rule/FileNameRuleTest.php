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
        yield 'pascal' => [
            'foo.feature',
            '',
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
