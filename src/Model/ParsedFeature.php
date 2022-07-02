<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\GherkinDocument;
use Cucumber\Messages\Scenario;
use Generator;

final class ParsedFeature
{
    public function __construct(
        private readonly GherkinDocument $document,
        private readonly string $source
    ) {
    }

    public function document(): GherkinDocument
    {
        return $this->document;
    }

    public function source(): string
    {
        return $this->source;
    }

    /**
     * @return Generator<Scenario>
     */
    public function scenarios(): Generator
    {
        if (!$this->document->feature) {
            return;
        }
        foreach ($this->document->feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }

            if (null === $child->scenario) {
                continue;
            }

            yield $child->scenario;
        }
    }

    public function lines(): void
    {
    }
}
