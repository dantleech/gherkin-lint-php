<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Gherkin\GherkinParser;
use Cucumber\Messages\GherkinDocument;
use Generator;

class Linter
{
    public function __construct(
        private GherkinParser $parser,
        /**
         * @var Rule[]
         */
        private array $rules,
        private RuleConfigFactory $configFactory
    ) {
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function lint(string $uri, string $contents): Generator
    {
        foreach ($this->gherkinDocuments($uri, $contents) as $document) {
            foreach ($this->rules as $rule) {
                $description = $rule->describe();

                if (false === $this->configFactory->isEnabled($description->name)) {
                    continue;
                }

                yield from $rule->analyse(
                    new ParsedFeature($document, $contents),
                    $this->configFactory->for($description)
                );
            }
        }
    }

    /**
     * @return Generator<GherkinDocument>
     */
    private function gherkinDocuments(string $uri, string $contents): Generator
    {
        foreach ($this->parser->parseString($uri, $contents) as $envelope) {
            if (!$envelope->gherkinDocument) {
                continue;
            }

            yield $envelope->gherkinDocument;
        }
    }
}
