<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\Scenario;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class NoDuplicatedScenarioNames implements Rule
{
    /**
     * @var array<string,string>
     */
    private array $seen = [];

    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        $scenarios = [];
        foreach ($document->feature->children ?? [] as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (!$child->scenario instanceof Scenario) {
                continue;
            }

            if (isset($scenarios[$child->scenario->name])) {
                yield new FeatureDiagnostic(
                    Range::fromLocationAndName($child->scenario->location, $child->scenario->name),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf(
                        'Scenario has already been defined on line %d',
                        $scenarios[$child->scenario->name]->location->line
                    )
                );
            }
            $scenarios[$child->scenario->name] = $child->scenario;
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            name: 'no-duplicated-scenario-names',
            description: 'Dissallow duplicated scenarios within feature files',
            examples: [
                new RuleExample(
                    title: 'No duplicated scenarios',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: One

                            Scenario: Two
                        EOT
                ),
                new RuleExample(
                    title: 'Duplicated scenarios',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: One

                            Scenario: One
                        EOT
                )
            ]
        );
    }
}
