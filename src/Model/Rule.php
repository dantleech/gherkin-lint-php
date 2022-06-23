<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\Envelope;
use Generator;

interface Rule
{
    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function analyse(Envelope $feature): Generator;
}
