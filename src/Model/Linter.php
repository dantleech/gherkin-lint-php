<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Gherkin\GherkinParser;

class Linter
{
    public function __construct(private GherkinParser $parser)
    {
    }

    public function lint(string $uri, string $contents): FeatureDiagnostics
    {
        $node = $this->parser->parseString($uri, $contents);

        return new FeatureDiagnostics([]);
    }
}
