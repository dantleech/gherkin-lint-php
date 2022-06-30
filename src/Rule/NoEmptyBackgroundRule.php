<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Background;
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

class NoEmptyBackgroundRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        foreach ($document->feature->children ?? [] as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (!$child->background instanceof Background) {
                continue;
            }

            if (count($child->background->steps) > 0) {
                continue;
            }

            yield new FeatureDiagnostic(
                Range::fromLocationAndName($child->background->location, $child->background->keyword),
                FeatureDiagnosticSeverity::WARNING,
                'Background must have at least one step'
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-empty-background',
            'Disallow empty backgrounds',
            null,
            examples: [
                new RuleExample(
                    title: 'Non-empty background',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Background:
                                Given something happened
                        EOT
                ),
                new RuleExample(
                    title: 'Empty background',
                    valid: false,
                    example: <<<'EOT'
                        Feature: Foobar
                            Background:
                        EOT
                )
            ]
        );
    }
}
