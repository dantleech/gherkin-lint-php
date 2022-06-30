<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class NoEmptyFileRule implements Rule
{
    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-empty-file',
            'Disallow empty files',
            examples: [
                new RuleExample(
                    title: 'Non-empty file',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                        EOT,
                ),
                new RuleExample(
                    title: 'Empty file',
                    valid: false,
                    example: '   ',
                ),
            ]
        );
    }

    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        if ($document->feature) {
            return;
        }

        yield new FeatureDiagnostic(
            Range::fromInts(0, 0, 0, 0),
            FeatureDiagnosticSeverity::WARNING,
            'Feature file is not allowed to be empty',
        );
    }
}
