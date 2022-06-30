<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class NoMultipleEmptyLinesRule implements Rule
{
    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
        yield;
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription('no-multiple-empty-lines', 'Do not permist multiple empty lines', null, []);
    }
}
