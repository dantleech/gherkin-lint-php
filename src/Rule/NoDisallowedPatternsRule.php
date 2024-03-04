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

class NoDisallowedPatternsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        assert($config instanceof NoDisallowedPatternsConfig);
        foreach ($config->patterns as $pattern) {
            if (preg_match_all($pattern, $feature->source(), $matches, PREG_OFFSET_CAPTURE) === false) {
                continue;
            }

            foreach ($matches[0] as [$match, $offset]) {
                yield new FeatureDiagnostic(
                    Range::fromByteOffsets($feature->source(), $offset, $offset + strlen($match)),
                    FeatureDiagnosticSeverity::WARNING,
                    sprintf('Text "%s" is not allowed by pattern "%s"', $match, $pattern)
                );
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-restricted-patterns',
            'Dissallow text matching any of the given patterns',
            NoDisallowedPatternsConfig::class,
            [
                new RuleExample(
                    valid: false,
                    title: 'Disallow the term "Client"',
                    example: <<<'EOT'
                        Feature: Client
                        EOT,
                    config: new NoDisallowedPatternsConfig([
                        '/client/i',
                    ]),
                )
            ]
        );
    }
}
