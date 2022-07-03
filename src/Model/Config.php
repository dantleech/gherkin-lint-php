<?php

namespace DTL\GherkinLint\Model;

use DTL\Invoke\Invoke;

class Config
{
    /**
     * @var array<string,ConfigRule>
     */
    public array $rules;

    /**
     * @param array<string,ConfigRule|array<string,mixed>> $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = array_map(
            fn (ConfigRule|array $configRule) => $configRule instanceof ConfigRule ? $configRule : Invoke::new(
                ConfigRule::class,
                $configRule
            ),
            $rules
        );
    }

    /**
     * @return string[]
     */
    public function enabledRules(): array
    {
        return array_keys(array_filter($this->rules, fn (ConfigRule $rule) => $rule->enabled));
    }
}
