<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\GherkinDocument;
use Cucumber\Messages\Scenario;
use Cucumber\Messages\Step;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class KeywordOrderRule implements Rule
{
    const ORDER = [
        'Given',
        'When',
        'Then'
    ];

    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
        if (!$document->feature) {
            return;
        }

        foreach ($document->feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if ($child->scenario) {
                yield from $this->scenarioDiagnostics($child->scenario);
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'keyword-order',
            'Ensure that keywords are in the correct order'
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function scenarioDiagnostics(Scenario $scenario): Generator
    {
        $steps = $scenario->steps;

        if (count($steps) === 0) {
            return;
        }

        $diagnostics = $this->diagnostics($steps, ['Given', 'When', 'Then']);

        yield from $diagnostics;
    }

    /**
     * @param Step[] $steps
     * @param array{string,string,string} $expectedOrder
     * @return list<FeatureDiagnostic>
     */
    private function diagnostics(array $steps, array $expectedOrder): array
    {
        $diagnostics = [];
        $lastIndex = null;
        foreach ($steps as $index => $step) {
            $keyword = trim($step->keyword);

            if ($index === 0 && !in_array($keyword, ['Given' ,'When'])) {
                $diagnostics[] = new FeatureDiagnostic(
                    Range::fromLocationAndName($step->location, $keyword),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf(
                        'First step must start with "Given" or "When", got "%s"',
                        $keyword
                    )
                );
                continue;
            }

            if ($index > 0 && in_array($keyword, ['But' ,'And'])) {
                continue;
            }

            $currentIndex = array_search($keyword, $expectedOrder);

            if (false === $currentIndex) {
                $diagnostics[] = new FeatureDiagnostic(
                    Range::fromLocationAndName($step->location, $keyword),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf(
                        'Keyword "%s" is not valid at this position',
                        $keyword
                    )
                );
            }

            if (null === $lastIndex) {
                $lastIndex = $currentIndex;
                continue;
            }

            if ($currentIndex > $lastIndex) {
                $lastIndex = $currentIndex;
                continue;
            }

            // keyword is before or at same position as last one
            $diagnostics[] = new FeatureDiagnostic(
                Range::fromLocationAndName($step->location, $keyword),
                FeatureDiagnosticSeverity::WARNING,
                sprintf(
                    'Keyword "%s" cannot come after a "%s"',
                    $expectedOrder[$currentIndex],
                    $expectedOrder[$lastIndex],
                )
            );
        }

        return $diagnostics;
    }
}
