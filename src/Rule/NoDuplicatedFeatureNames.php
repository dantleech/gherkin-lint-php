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

class NoDuplicatedFeatureNames implements Rule
{
    /**
     * @var array<string,string>
     */
    private array $seen = [];

    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        $feature = $document->feature;
        if (!$feature) {
            return;
        }

        if (null === $document->uri) {
            return;
        }

        if (isset($this->seen[$feature->name])) {
            yield new FeatureDiagnostic(
                Range::fromLocationAndName($feature->location, $feature->name),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Feature already defined in "%s"', $this->seen[$feature->name])
            );
            return;
        }

        $this->seen[$feature->name] = $document->uri;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-duplicated-feature-names',
            'Dissallow duplicated feature names',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'Feature with unique title',
                    example: 'Feature: this feature title is one of a kind',
                )
            ]
        );
    }
}
