<?php

namespace DTL\GherkinLint\Model;

class RuleDescription
{
    public function __construct(public readonly string $name, public readonly string $description)
    {
    }
}
