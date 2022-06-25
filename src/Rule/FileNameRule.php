<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use Generator;

class FileNameRule implements Rule
{
    public function analyse(GherkinDocument $document, RuleConfig $config): Generator
    {
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'file-name',
            'Filenames must conform to the specified stype',
            FileNameConfig::class
        );
    }
}
