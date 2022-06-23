<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\Feature;
use Generator;

class AstTraverser
{
    public function __construct(
        /** @var Rule[] */
        private array $rules
    )
    {
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function traverse(object $node): Generator
    {
        foreach ($this->rules as $rule) {
            yield from $rule->visit($node);
        }
    }
}
