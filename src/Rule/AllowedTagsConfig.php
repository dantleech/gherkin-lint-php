<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class AllowedTagsConfig implements RuleConfig
{
    public function __construct(
        /** @var null|string[] */
        public ?array $allow = null
    ) {
    }
}
