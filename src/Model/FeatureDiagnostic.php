<?php

namespace DTL\GherkinLint\Model;

class FeatureDiagnostic
{
    public function __construct(
        public Range $range,
        public string $severity,
        public string $message
    ) {
    }
}
