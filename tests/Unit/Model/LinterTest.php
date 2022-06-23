<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Rule\TestRule;
use Generator;
use PHPUnit\Framework\TestCase;

class LinterTest extends TestCase
{
    /**
     * @dataProvider provideLint
     */
    public function testLint(string $content): void
    {
        $diagnostics = iterator_to_array((new Linter(
            new GherkinParser(),
            [
                new TestRule([
                    new FeatureDiagnostic(Range::fromInts(1, 1, 2, 2), FeatureDiagnosticSeverity::WARNING, 'Foo'),
                ])
            ]
        ))->lint('/path', $content));

        self::assertCount(1, $diagnostics);
    }

    /**
     * @return Generator<array{string}>
     */
    public function provideLint(): Generator
    {
        yield [
            <<<'EOT'
                    Feature: Foobar

                        Scenario: Foobar
                            Given this happened
                            When I do this
                            Then that should happen
                EOT
        ];
    }
}
