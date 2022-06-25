<?php

namespace DTL\GherkinLint\Rule;

enum FileNameStyle: string {
    case PASCAL_CASE = 'PascalCase';
    case TITLE_CASE = 'Title Case';
    case CAMEL_CASE = 'camelCase';
    case SNAKE_CASE = 'snake_case';
    case KEBAB_CASE = 'kebab-case';
}
