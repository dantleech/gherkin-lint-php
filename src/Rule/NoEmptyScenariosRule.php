<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class NoEmptyScenariosRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        foreach ($feature->scenarios() as $scenario) {
            if (empty($scenario->steps)) {
                yield new FeatureDiagnostic(
                    Range::fromLocationAndName($scenario->location, $scenario->keyword),
                    FeatureDiagnosticSeverity::WARNING,
                    'Scenario does not have any steps'
                );
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-empty-scenarios',
            'Disallow empty scenarios',
            null,
            [
                new RuleExample(
                    title: 'Scenarios that are empty',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Example
                            Scenario: One
                            Scenario: Two
                        EOT
                ),
                new RuleExample(
                    title: 'Scenarios that are not empty',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Example
                            Scenario: One
                                When I do this
                                Then this should happen

                            Scenario: Two
                                When I do this
                                Then this should happen
                        EOT
                ),
            ]
        );
    }
}
