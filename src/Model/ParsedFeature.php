<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\GherkinDocument;

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
}
