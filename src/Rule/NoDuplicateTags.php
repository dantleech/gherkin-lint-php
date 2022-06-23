<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Envelope;
use Cucumber\Messages\GherkinDocument;
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
        yield new FeatureDiagnostic(
            Range::fromInts(1, 1, 1, 1),
            FeatureDiagnosticSeverity::WARNING,
            sprintf('Tag is duplicated')
        );
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-duplicate-tags',
            'Disallow duplicate tags'
        );
    }
}
