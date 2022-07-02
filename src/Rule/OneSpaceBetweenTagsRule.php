<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
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

class OneSpaceBetweenTagsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $feature = $feature->document()->feature;

        if (null === $feature) {
            return;
        }

        yield from $this->checkTags($feature->tags);

        foreach ($feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (!$child->scenario) {
                continue;
            }

            yield from $this->checkTags($child->scenario->tags);
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'one-space-between-tags',
            'Only allow one space between tags',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'Tags have one space between them',
                    example: <<<'EOT'
                        @tag1 @tag2 @tag3
                        Feature: Foobar
                            @tag4 @tag5
                            Scenario: Barfoo
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Tags have more than one space between them',
                    example: <<<'EOT'
                        @tag1   @tag2  @tag3
                        Feature: Foobar
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Tags have more than one space between them',
                    example: <<<'EOT'
                        Feature: Foobar
                            @tag1    @tag2
                            Scenario: Barfoo
                        EOT
                ),
            ]
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     * @param Tag[] $tags
     */
    private function checkTags(array $tags): Generator
    {
        $lastEnd = null;
        $currentLine = null;
        foreach ($tags as $tag) {
            if (null === $tag->location->column) {
                continue;
            }

            if ($currentLine !== $tag->location->line) {
                $currentLine = null;
            }

            if ($lastEnd === null || $currentLine === null) {
                $lastEnd = $tag->location->column + mb_strlen($tag->name);
                $currentLine = $tag->location->line;
                continue;
            }

            $nbSpaces = $tag->location->column - $lastEnd;

            if ($nbSpaces !== 1) {
                yield new FeatureDiagnostic(
                    Range::fromLocationAndName($tag->location, $tag->name),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf(
                        'Only one space allowed before tag %s, got %d',
                        $tag->name,
                        $nbSpaces
                    ),
                );
            }

            $lastEnd = $tag->location->column + mb_strlen($tag->name);
        }
    }
}
