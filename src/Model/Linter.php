<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Gherkin\GherkinParser;

class Linter
{
    public function __construct(private GherkinParser $parser, private AstTraverser $traverser)
    {
    }

    public function lint(string $uri, string $contents): FeatureDiagnostics
    {
        return new FeatureDiagnostics(
            iterator_to_array(
                $this->traverser->traverse(
                    $this->parser->parseString($uri, $contents)
                )
            )
        );
    }
}
