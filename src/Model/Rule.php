<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\GherkinDocument;
use Generator;

interface Rule
{
    /**
     * @return Generator<mixed,FeatureDiagnostic,mixed,void>
     */
    public function analyse(GherkinDocument $document): Generator;

    public function describe(): RuleDescription;
}
