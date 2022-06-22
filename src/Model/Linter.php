<?php

namespace DTL\BehatLint\Model;

class Linter
{
    public function lint(string $path): FeatureDiagnostics
    {
        return new FeatureDiagnostics([
            new FeatureDiagnostic(
                new Position(10, 10),
                FeatureDiagnosticSeverity::WARNING,
                'Sorry'
            )
        ]);
    }
}
