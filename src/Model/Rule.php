<?php

namespace DTL\GherkinLint\Model;

use Generator;

interface Rule
{
    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function visit(object $node): Generator;
}
