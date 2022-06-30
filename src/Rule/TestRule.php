<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class TestRule implements Rule
{
    /**
     * @param FeatureDiagnostic[] $diagnostics
     */
    public function __construct(private string $name, private array $diagnostics = [])
    {
        yield new FeatureDiagnostic(
            Range::fromInts(0, 0, 0, 0),
            FeatureDiagnosticSeverity::WARNING,
            'Feature file is not allowed to be empty',
        );
    }

    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        yield from $this->diagnostics;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription($this->name, 'Test rule');
    }
}
