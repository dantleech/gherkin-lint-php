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
use DTL\GherkinLint\Rule\Util\DocumentQuery;
use Generator;

class NoBackgroundWithSingleScenarioRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        $scenarioCount = DocumentQuery::countScenarios($document);
        $background = null;

        foreach ($document->feature?->children ?? [] as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if ($child->background) {
                $background = $child->background;
                break;
            }
        }

        if ($scenarioCount === 1 && $background) {
            yield new FeatureDiagnostic(
                Range::fromLocationAndName($background->location, $background->name),
                FeatureDiagnosticSeverity::WARNING,
                'Background is only permitted if there is more than one scenario'
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-background-with-single-scenario',
            'Backgrounds are only allowed when there is more than one scenario',
            null,
            [
                new RuleExample(
                    title: 'Background with more than one scenario',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Background:
                                Given I have stuff

                            Scenario: One
                            Scenario: Two
                        EOT
                ),
                new RuleExample(
                    title: 'Background with one scenario',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Background:
                                Given I have stuff

                            Scenario: One
                        EOT
                ),
            ],
        );
    }
}
