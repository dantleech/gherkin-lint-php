<?php

namespace DTL\GherkinLint\Rule;

use DTL\GherkinLint\Model\RuleConfig;

class IndentationConfig implements RuleConfig
{
    public function __construct(
        // number of spaces (or tabs) to use per indentation level
        public int $width = 4,
        public int $feature = 0,
        public int $rule = 1,
        public int $backgroud = 1,
        public int $scenario = 1,
        public int $step = 2,
        public int $table = 3,
        public int $literalBlock = 2,
        public int $examples = 2,
        public int $examplesTable = 3,
    ) {
    }
}
