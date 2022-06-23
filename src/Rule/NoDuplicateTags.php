<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Envelope;
use Cucumber\Messages\GherkinDocument;
use Cucumber\Messages\Tag;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;
use LanguageServerProtocol\DiagnosticTag;

class NoDuplicateTags implements Rule
{
    public function analyse(GherkinDocument $document): Generator
    {
        yield from $this->checkTags($document?->feature?->tags);
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-duplicate-tags',
            'Disallow duplicate tags'
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
