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

class NoHomogenousTagsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        if (!$document->feature) {
            return;
        }

        $tagCounts = [];
        $scnearioCount = 0;
        foreach ($document->feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (null === $child->scenario) {
                continue;
            }

            $scnearioCount++;

            foreach ($child->scenario->tags as $tag) {
                if (!isset($tagCounts[$tag->name])) {
                    $tagCounts[$tag->name] = [$tag];
                    continue;
                }

                $tagCounts[$tag->name][] = $tag;
            }
        }

        $tagCounts = array_filter($tagCounts, fn (array $tags) => count($tags) === $scnearioCount);

        foreach ($tagCounts as $tags) {
            foreach ($tags as $tag) {
                yield new FeatureDiagnostic(
                    Range::fromLocationAndName($tag->location, $tag->name),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf('Tag "%s" is present on each Scenario, move to Feature', $tag->name)
                );
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-homogenous-tags',
            'If a tag exists on each scenarion then it should be moved to the feature level',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'No tags are present on all scenarios',
                    example: <<<'EOT'
                        Feature: Good feature
                            @one
                            Scenario: One
                            
                            @two
                            Scenario: Two
                            
                            @three
                            Scenario: Three
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'One tag is present in all scenarios',
                    example: <<<'EOT'
                        Feature: Bad feature
                            @one
                            Scenario: One
                            
                            @two @one
                            Scenario: Two
                            
                            @three @one
                            Scenario: Three
                        EOT
                )
            ]
        );
    }
}
