<?php

namespace DTL\GherkinLint\Model;

use RuntimeException;

class RuleCollection
{
    /**
     * @var array<string,Rule>
     */
    private array $rules;

    /** 
     * @param Rule[] $rules 
     * @param string[] $enabledRules
     */
    public function __construct(
        array $rules,
        private array $enabledRules,
    )
    {
        $this->rules = array_combine(
            array_map(
                fn (Rule $rule): string => $rule->describe()->name,
                $rules,
            ),
            array_values($rules)
        );
    }

    /**
     * @return Rule[]
     */
    public function rules(): array
    {
        $rules = [];
        foreach ($this->enabledRules as $enabledRule) {
            $rules[] = $this->get($enabledRule);
        }

        return $rules;
    }

    private function get(string $rule): Rule
    {
        if (!isset($this->rules[$rule])) {
            throw new RuntimeException(sprintf(
                'Rule "%s" is not known, known rules: "%s"',
                $rule, implode('", "', array_keys($this->rules))
            ));
        }

        return $this->rules[$rule];
    }

}
