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

class NoUnnamedFeaturesRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $feature = $feature->document()->feature;

        if (null === $feature) {
            return;
        }

        if (trim($feature->name) !== '') {
            return;
        }

        yield new FeatureDiagnostic(
            Range::fromLocationAndName($feature->location, $feature->keyword),
            FeatureDiagnosticSeverity::WARNING,
            'Unnamed features are not permitted'
        );
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-unnamed-features',
            'Do not allow Feature declarations with no name',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'Feature with a name',
                    example: <<<'EOT'
                        Feature: This feature has a name!
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Feature with no name',
                    example: <<<'EOT'
                        Feature:
                        EOT
                ),
            ]
        );
    }
}
