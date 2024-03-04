<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class NoDisallowedPatternsConfig implements RuleConfig
{
    public function __construct(
        /** @var list<non-empty-string> */
        public readonly array $patterns = []
    ) {
    }
}
