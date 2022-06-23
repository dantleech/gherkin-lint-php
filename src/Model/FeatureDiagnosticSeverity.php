<?php

namespace DTL\GherkinLint\Model;

enum FeatureDiagnosticSeverity: string
{
    case ERROR = 'error';
    case WARNING = 'warning';

    public function toString(): string
    {
        return $this->value;
    }
}
