<?php

namespace DTL\GherkinLint\Model;

class Config
{
    /**
     * @var array<string,ConfigRule>
     */
    public array $rules = [];

    /**
     * @return string[]
     */
    public function enabledRules(): array
    {
        return array_keys($this->rules);
    }
}
