<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use Cucumber\Gherkin\GherkinParser;
use Cucumber\Messages\Feature;
use DTL\GherkinLint\Model\AstTraverser;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Rule\TestRule;
use PHPUnit\Framework\TestCase;

class AstTraverserTest extends TestCase
{
    public function testProvidesDiagnostics(): void
    {
        $feature = <<<'EOT'
Feature: Foobar

    Scenario: Foo
       Given I this
       Then that
EOT
        ;

        $diagnostics = $this->traverse($feature, new TestRule([
            new FeatureDiagnostic(Range::fromInts(1, 1, 10, 10), FeatureDiagnosticSeverity::WARNING, 'Foobar')
        ]));

        self::assertCount(1, $diagnostics);
        self::assertEquals('Foobar', $diagnostics[0]->message);
    }

    /**
     * @return FeatureDiagnostic[]
     */
    private function traverse(string $feature, TestRule $testRule): array
    {
        $node = (new GherkinParser())->parseString('/foo', $feature);
        
        $diagnostics = iterator_to_array((new AstTraverser([
            $testRule
        ]))->traverse($node));
        return $diagnostics;
    }
}
