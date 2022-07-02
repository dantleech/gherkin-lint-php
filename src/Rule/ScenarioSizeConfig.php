<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class ScenarioSizeConfig implements RuleConfig
{
    public function __construct(
        public readonly int $maxSteps = 10,
    ) {
    }
}
