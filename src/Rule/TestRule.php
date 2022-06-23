<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Envelope;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class TestRule implements Rule
{
    /**
     * @param FeatureDiagnostic[] $diagnostics
     */
    public function __construct(private array $diagnostics = [])
    {
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function analyse(Envelope $feature): Generator
    {
        yield from $this->diagnostics;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription('test', 'Test rule');
    }
}
