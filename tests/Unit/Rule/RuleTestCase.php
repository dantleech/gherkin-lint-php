<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use Closure;
use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Rule;
use Generator;
use PHPUnit\Framework\TestCase;

abstract class RuleTestCase extends TestCase
{
    abstract public function createRule(): Rule;

    /**
     * @return Generator<string,Closure(list<FeatureDiagnostic>):void>
     */
    abstract public function provideTests(): Generator;

    /**
     * @dataProvider provideTests
     */
    public function testRule(string $contents, Closure $assertion): void
    {
        $assertion(new FeatureDiagnostics(iterator_to_array((new Linter(
            new GherkinParser(
                includePickles: false,
                includeSource: false,
                includeGherkinDocument: true,
            ),
            [$this->createRule()]
        ))->lint('/path', $contents))));
    }
}
