<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Gherkin\GherkinParser;
use Generator;

class Linter
{
    public function __construct(
        private GherkinParser $parser,
        /**
         * @var Rule[]
         */
        private array $rules
    ) {
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function lint(string $uri, string $contents): Generator
    {
        $envelopes = iterator_to_array($this->parser->parseString($uri, $contents));

        foreach ($envelopes as $envelope) {
            foreach ($this->rules as $rule) {
                $document = $envelope->gherkinDocument;
                if (null === $document) {
                    continue;
                }

                yield from $rule->analyse($document);
            }

            // why multiple envelopes?
            break;
        }
    }
}
