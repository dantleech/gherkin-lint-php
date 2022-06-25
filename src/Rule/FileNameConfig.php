<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class FileNameConfig implements RuleConfig
{
    public function __construct(
        /**
         * @var 'PascalCase'|'TitleCase'|'camelCase'|'snake-case'|'kebab-case'
         */
        public string $style,
    ){}
}
