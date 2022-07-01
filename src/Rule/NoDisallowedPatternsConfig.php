<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class NoDisallowedPatternsConfig implements RuleConfig
{
    public function __construct(
        /** @var string[] */
        public readonly array $patterns = []
    ) {
    }
}
