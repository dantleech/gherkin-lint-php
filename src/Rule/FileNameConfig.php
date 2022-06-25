<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class FileNameConfig implements RuleConfig
{
    public const PASCAL_CASE = 'PascalCase';
    public const TITLE_CASE = 'Title Case';
    public const CAMEL_CASE = 'camelCase';
    public const SNAKE_CASE = 'snake-case';
    public const KEBAB_CASE = 'kebab-case';

    public function __construct(
        /**
         * @var 'PascalCase'|'TitleCase'|'camelCase'|'snake-case'|'kebab-case'
         */
        public string $style,
    ){}
}
