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
use DTL\GherkinLint\Util\StringUtil;
use Generator;

class NoTrailingSpacesRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        foreach (StringUtil::lines($feature->source()) as $lineOffset => $line) {
            $lineNb = (int)$lineOffset + 1;
            if (!preg_match('{(\s+)$}', $line, $matches)) {
                continue;
            }
            $whitespace = $matches[1];
            yield new FeatureDiagnostic(
                Range::fromInts($lineNb, mb_strlen($line) - mb_strlen($whitespace) + 1, $lineNb, mb_strlen($line)),
                FeatureDiagnosticSeverity::WARNING,
                'Trailing whitespace is not permitted'
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-trailing-spaces',
            'Do not allow extra spaces at the end of lines',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'No trailing spaces',
                    example: <<<'EOT'
                        Feature: Foobar

                        There are no trailing spaces on this line
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Trailing spaces',
                    example: <<<'EOT'
                        Feature: Foobar

                        There are trailing spaces on this line    
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Trailing spaces',
                    example: <<<'EOT'
                        Feature: Foobar
                         
                        There are trailing spaces above
                        EOT
                )
            ]
        );
    }
}
