<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\Scenario;
use Cucumber\Messages\Step;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class KeywordOrderRule implements Rule
{
    private const KW_GIVEN = 'Given';
    private const KW_WHEN = 'When';
    private const KW_THEN = 'Then';

    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        assert($config instanceof KeywordOrderConfig);

        if (!$document->feature) {
            return;
        }

        foreach ($document->feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if ($child->scenario) {
                yield from $this->scenarioDiagnostics($config, $child->scenario);
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'keyword-order',
            'Ensure that keywords are in the correct order',
            KeywordOrderConfig::class,
            examples: [
                new RuleExample(
                    title: 'Keywords in correct order',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                Given this is a scenario
                                And the indentation is incorrect
                                When I run the linter
                                Then things will not be good
                        EOT,
                ),
                new RuleExample(
                    title: 'Extra when is not allowed',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                Given this is a scenario
                                And the indentation is incorrect
                                When I run the linter
                                Then things will not be good
                                When I do something else
                        EOT,
                ),
                new RuleExample(
                    title: 'Scenarios cannot start with Then',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                Then things will not be good
                        EOT,
                ),
                new RuleExample(
                    title: 'Scenarios cannot start with And',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                And things will not be good
                        EOT,
                ),
                new RuleExample(
                    title: 'Tolerate then before when with config option',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                Given something
                                Then an exception should be thrown
                                When I do this
                        EOT,
                    config: new KeywordOrderConfig(tolerateThenBeforeWhen: true),
                ),
            ]
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function scenarioDiagnostics(KeywordOrderConfig $config, Scenario $scenario): Generator
    {
        $steps = $scenario->steps;

        if (count($steps) === 0) {
            return;
        }

        $diagnostics = $this->diagnostics($steps, [self::KW_GIVEN, self::KW_WHEN, self::KW_THEN]);

        if ($diagnostics > 1 && $config->tolerateThenBeforeWhen) {
            if (0 === count($this->diagnostics($steps, [self::KW_GIVEN, self::KW_THEN, self::KW_WHEN]))) {
                return;
            }
        }

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

            if ($index === 0 && !in_array($keyword, [self::KW_GIVEN ,self::KW_WHEN])) {
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
