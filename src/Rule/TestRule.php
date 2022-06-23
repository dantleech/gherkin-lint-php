<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\GherkinDocument;
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

    public function analyse(GherkinDocument $document): Generator
    {
        yield from $this->diagnostics;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription('test', 'Test rule');
    }
}
