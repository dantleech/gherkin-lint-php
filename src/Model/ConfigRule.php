<?php

namespace DTL\GherkinLint\Model;

class ConfigRule
{
    public function __construct(
        public bool $enabled = false,
        /**
         * @var array<string,mixed>
         */
        public array $config = [],
    ){}
}
