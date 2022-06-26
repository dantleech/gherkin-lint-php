<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
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
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                        EOT,
                    config: new KeywordOrderConfig(tolerateThenBeforeWhen: true),
                ),
                new RuleExample(
                    valid: false,
                    example: '   ',
                    config: new KeywordOrderConfig(tolerateThenBeforeWhen: true),
                ),
            ]
        );
    }

    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
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
