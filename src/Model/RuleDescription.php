<?php

namespace DTL\GherkinLint\Model;

class RuleDescription
{
    public function __construct(public readonly $name, public readonly $description)
    {
    }
}
