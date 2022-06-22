<?php

namespace DTL\BehatLint\Model;

class FeatureDiagnostic
{
    public function __construct(
        public Position $position,
        public string $severity,
        public string $message
    ) {
    }
}
