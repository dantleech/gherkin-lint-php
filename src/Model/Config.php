<?php

namespace DTL\GherkinLint\Model;

class Config
{
    public function __construct(
        /**
         * @var array<string,ConfigRule>
         */
        public array $rules = []
    )
    {
    }

    /**
     * @return string[]
     */
    public function enabledRules(): array
    {
        return array_keys(array_filter($this->rules, fn (ConfigRule $rule) => $rule->enabled));
    }
}
