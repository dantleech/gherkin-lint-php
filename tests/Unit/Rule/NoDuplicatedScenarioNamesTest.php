<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDuplicatedScenarioNames;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoDuplicatedScenarioNamesTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDuplicatedScenarioNames();
    }

    public static function provideTests(): Generator
    {
        yield 'duplicated scenario' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foobar
                        Scenario: Foo
                        Scenario: Foo
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals('Scenario has already been defined on line 2', $diagnostics->first()->message);
            },
            [
            ]
        ];
    }
}
