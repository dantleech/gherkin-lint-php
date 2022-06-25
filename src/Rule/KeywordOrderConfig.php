<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class KeywordOrderConfig implements RuleConfig
{
    public function __construct(
        public bool $tolerateThenBeforeWhen = false
    ) {
    }
}
