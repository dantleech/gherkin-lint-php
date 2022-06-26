<?php

namespace DTL\GherkinLint\Model;

class RuleCollection
{
    /**
     * @var array<string,Rule>
     */
    private array $rules;

    /**
     * @param Rule[] $rules
     */
    public function __construct(
        array $rules,
    ) {
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
        return $this->rules;
    }
}
