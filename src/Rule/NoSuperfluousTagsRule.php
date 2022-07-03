<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Tag;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class NoSuperfluousTagsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $featureNode = $feature->document()->feature;
        if (null === $featureNode) {
            return;
        }
        $featureTags = array_combine(array_map(fn (Tag $tag) => $tag->name, $featureNode->tags), $featureNode->tags);

        foreach ($feature->scenarios() as $scenario) {
            foreach ($scenario->tags as $tag) {
                if (!array_key_exists($tag->name, $featureTags)) {
                    continue;
                }

                yield new FeatureDiagnostic(
                    Range::fromLocationAndName($tag->location, $tag->name),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf(
                        'Tag "%s" is already define on the Feature on line %d',
                        $tag->name,
                        $featureTags[$tag->name]->location->line
                    )
                );
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-superflous-tags',
            'Do not repeat tags in scenarios that are already present at the feature level',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'No superflous tags',
                    example: <<<'EOT'
                        @important
                        Feature: Foobar

                            @this-there @is @no-waste
                            Scenario: No waste
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Tag that is repeated in the Feature',
                    example: <<<'EOT'
                        @important
                        Feature: Foobar

                            @this-there @is @no-waste @important
                            Scenario: No waste
                        EOT
                )
            ]
        );
    }
}
