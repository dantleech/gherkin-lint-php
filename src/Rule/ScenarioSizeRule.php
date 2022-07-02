<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class ScenarioSizeRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        assert($config instanceof ScenarioSizeConfig);

        $feature = $feature->document()->feature;
        if (null === $feature) {
            return;
        }

        foreach ($feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (!$child->scenario) {
                continue;
            }

            $stepsCount = count($child->scenario->steps);
            if ($stepsCount <= $config->maxSteps) {
                continue;
            }

            yield new FeatureDiagnostic(
                Range::fromLocationAndName($child->scenario->location, $child->scenario->name),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Scenario has %d steps, but configured maximum number is %d', $stepsCount, $config->maxSteps)
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'scenario-size',
            'Limit the number of steps in a scenario',
            ScenarioSizeConfig::class,
            [
                new RuleExample(
                    valid: true,
                    title: 'Valid number of steps',
                    example: <<<'EOT'
                        Feature: This is feature
                            Scenario: This is scenario
                                Given I did this
                                When I do that
                                Then this should happen
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Too many steps!',
                    config: new ScenarioSizeConfig(maxSteps: 3),
                    example: <<<'EOT'
                        Feature: This is feature
                            Scenario: This is scenario
                                Given I did this
                                And that
                                And something else
                                When I do that
                                Then this should happen
                        EOT
                ),
            ]
        );
    }
}
