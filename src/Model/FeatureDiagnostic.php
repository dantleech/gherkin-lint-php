<?php

namespace DTL\GherkinLint\Model;

class FeatureDiagnostic
{
    public function __construct(
        public Range $range,
        public FeatureDiagnosticSeverity $severity,
        public string $message
    ) {
    }
}
