<?php

namespace DTL\GherkinLint\Model;

class RuleExample
{
    public function __construct(
        public string $title,
        public bool $valid,
        public string $example,
        public ?RuleConfig $config = null,
        public string $filename = 'example.feature'
    ) {
    }
}
