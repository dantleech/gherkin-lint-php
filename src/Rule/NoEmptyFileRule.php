<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Envelope;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class NoEmptyFileRule implements Rule
{
    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-empty-file',
            'Disallow empty files'
        );
    }

    public function analyse(Envelope $feature): Generator
    {
        if (!empty($feature->source?->data)) {
            return;
        }

        yield new FeatureDiagnostic(
            Range::fromInts(0, 0, 0, 0),
            FeatureDiagnosticSeverity::WARNING,
            'Feature file is not allowed be empty'
        );
    }
}
