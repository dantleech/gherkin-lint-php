<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;

class NoDisallowedPatternsConfig implements RuleConfig
{
    public function __construct(
        public readonly array $patterns = []
    ) {}
}
