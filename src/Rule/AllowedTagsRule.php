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

class AllowedTagsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        assert($config instanceof AllowedTagsConfig);

        if (null === $config->allow) {
            return;
        }

        yield from $this->checkTags(
            $document->feature?->tags,
            $config->allow
        );

        foreach ($document->feature->children ?? [] as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }
            if (!$child->scenario instanceof Scenario) {
                continue;
            }

            yield from $this->checkTags($child->scenario->tags, $config->allow);
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'allowed-tags',
            'Only permit specified tags',
            AllowedTagsConfig::class,
            examples: [
                new RuleExample(
                    title: 'Feature has allowed tags',
                    valid: true,
                    example: <<<'EOT'
                        @foo @bar
                        Feature: Some feature
                        EOT,
                    config: new AllowedTagsConfig(['@foo', '@bar'])
                ),
                new RuleExample(
                    'Feature has not allowed tags',
                    false,
                    <<<'EOT'
                        @this-is-not-allowed
                        Feature: Some feature
                        EOT
                    ,
                    config: new AllowedTagsConfig(['@baz']),
                ),
            ]
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     * @param ?list<Tag> $tags
     * @param string[] $allowedTags
     */
    private function checkTags(?array $tags, array $allowedTags): Generator
    {
        if (null === $tags) {
            return;
        }

        foreach ($tags as $tag) {
            if (in_array($tag->name, $allowedTags)) {
                continue;
            }

            yield new FeatureDiagnostic(
                Range::fromLocationAndName($tag->location, $tag->name),
                FeatureDiagnosticSeverity::WARNING,
                sprintf('Tag "%s" is not allowed', $tag->name)
            );
        }
    }
}
