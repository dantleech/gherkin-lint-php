<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\Envelope;
use Cucumber\Messages\GherkinDocument;
use Generator;

interface Rule
{
    /**
     * @return Generator<int,FeatureDiagnostic,mixed,void>
     */
    public function analyse(GherkinDocument $feature): Generator;

    public function describe(): RuleDescription;
}
