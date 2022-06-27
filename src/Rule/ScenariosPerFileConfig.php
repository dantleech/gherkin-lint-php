<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class ScenariosPerFileConfig implements RuleConfig
{
    public function __construct(
        public readonly int $min = 0,
        public readonly ?int $max = null,
    ) {
    }
}
