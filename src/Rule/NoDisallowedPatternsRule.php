<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class NoDisallowedPatternsRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        yield;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'no-restricted-patterns',
            'Dissallow text matching any of the given patterns',
            NoDisallowedPatternsConfig::class,
            [
            ]
        );
    }
}
