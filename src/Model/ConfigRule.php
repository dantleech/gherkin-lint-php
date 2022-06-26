<?php

namespace DTL\GherkinLint\Model;

class ConfigRule
{
    public function __construct(
        public bool $enabled = true,
        /**
         * @var array<string,mixed>
         */
        public array $config = [],
    ) {
    }
}
