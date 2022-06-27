<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class ScenariosPerFileRule implements Rule
{
    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
        assert($config instanceof ScenariosPerFileConfig);

        $count = 0;
        foreach ($document->feature?->children ?? [] as $featureChild) {
            if (!$featureChild instanceof FeatureChild) {
                continue;
            }

            if ($featureChild->scenario) {
                $count++;
            }
        }

        if (null !== $config->max && $count > $config->max) {
            yield new FeatureDiagnostic(
                Range::fromInts(1, 1, 1, 1),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Feature has %d scenarios but max number is %d', $count, $config->max)
            );
        }

        if ($count < $config->min) {
            yield new FeatureDiagnostic(
                Range::fromInts(1, 1, 1, 1),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Feature has %d scenarios but min number is %d', $count, $config->min)
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'scenarios-per-file',
            'Set a maximum (and/or minimum) number of scenarios allowed per file',
            ScenariosPerFileConfig::class,
            [
                new RuleExample(
                    valid: true,
                    example: <<<'EOT'
                        Feature: One
                            Scenario: One
                            Scenario: Two
                            Scenario: Three
                        EOT,
                    config: new ScenariosPerFileConfig(min: 1, max: 3)
                ),
                new RuleExample(
                    valid: false,
                    example: <<<'EOT'
                        Feature: One
                            Scenario: First scenario
                            Scenario: Two
                        EOT,
                    config: new ScenariosPerFileConfig(min: 0, max: 1)
                ),
                new RuleExample(
                    valid: false,
                    example: <<<'EOT'
                        Feature: One
                            Scenario: One
                        EOT,
                    config: new ScenariosPerFileConfig(min: 5, max: 10)
                )
            ]
        );
    }
}
