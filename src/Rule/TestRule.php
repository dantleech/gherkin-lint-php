<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\Rule;
use Generator;

class TestRule implements Rule
{
    /**
     * @var object[]
     */
    private array $visited = [];

    /**
     * @param FeatureDiagnostic[] $diagnostics
     */
    public function __construct(private array $diagnostics = [])
    {
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function visit(object $node): Generator
    {
        $this->visited[] = $node;

        yield from $this->diagnostics;
    }
}
