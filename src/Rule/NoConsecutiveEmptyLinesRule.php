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

class NoConsecutiveEmptyLinesRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $lines = preg_split('{\r\n|\n|\r}', $feature->source());
        if (false === $lines) {
            return;
        }

        $emptyLines = [];
        foreach ($lines as $lineNo => $line) {
            $lineNo = $lineNo + 1;

            if (trim($line) === '') {
                $emptyLines[] = $lineNo;
            }
        }

        $prev = null;
        $start = null;

        foreach ($emptyLines as $lineNo) {
            if (null === $prev) {
                $prev = $lineNo;
                continue;
            }

            if ($start && $prev + 1 !== $lineNo) {
                yield new FeatureDiagnostic(
                    Range::fromInts($start, 1, $prev, 1),
                    FeatureDiagnosticSeverity::WARNING,
                    'Consecutive empty lines are dissallowed'
                );
                $start = null;
                $prev =  $lineNo;
                continue;
            }

            if (null === $start && $prev + 1 === $lineNo) {
                $start = $prev + 1;
            }

            $prev = $lineNo;
        }

        if ($start && $prev) {
            yield new FeatureDiagnostic(
                Range::fromInts($start, 1, $prev, 1),
                FeatureDiagnosticSeverity::WARNING,
                'Consecutive empty lines are dissallowed'
            );
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-consecutive-empty-lines',
            'Do not permit consecutive empty lines',
            null,
            [
                new RuleExample(
                    valid: true,
                    title: 'No consecutive empty lines',
                    example: <<<'EOT'
                        Feature: Foo

                            Scenario: One

                            Scenario: Two

                            Scenario: Three
                        EOT
                ),
                new RuleExample(
                    valid: false,
                    title: 'Consecutive empty lines',
                    example: <<<'EOT'
                        Feature: Foo


                            Scenario: One

                            Scenario: Two


                            Scenario: Three
                        EOT
                ),
            ]
        );
    }
}
