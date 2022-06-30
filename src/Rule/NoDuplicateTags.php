<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\Scenario;
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

class NoDuplicateTags implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        yield from $this->checkTags($document->feature?->tags);

        foreach ($document->feature->children ?? [] as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (!$child->scenario instanceof Scenario) {
                continue;
            }

            yield from $this->checkTags($child->scenario->tags);
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-duplicate-tags',
            'Disallow duplicate tags',
            examples: [
                new RuleExample(
                    'No duplicate tags',
                    true,
                    <<<'EOT'
                        @foo @bar
                        Feature: Some feature
                        EOT
                ),
                new RuleExample(
                    'Duplicated tags',
                    false,
                    <<<'EOT'
                        @foo @foo
                        Feature: Some feature
                        EOT
                ),
            ]
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     * @param ?list<Tag> $tags
     */
    private function checkTags(?array $tags): Generator
    {
        if (null === $tags) {
            return;
        }

        $seen = [];
        foreach ($tags as $tag) {
            if (!isset($seen[$tag->name])) {
                $seen[$tag->name] = true;
                continue;
            }

            yield new FeatureDiagnostic(
                Range::fromLocationAndName($tag->location, $tag->name),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Tag "%s" is a duplicate', $tag->name)
            );
        }
    }
}
