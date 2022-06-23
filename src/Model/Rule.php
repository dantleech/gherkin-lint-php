<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\Envelope;
use Generator;

interface Rule
{
    /**
     * @return Generator<int,FeatureDiagnostic,mixed,void>
     */
    public function analyse(Envelope $feature): Generator;

    public function describe(): RuleDescription;
}
