<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Gherkin\GherkinParser;
use Cucumber\Messages\Comment;
use Cucumber\Messages\GherkinDocument;
use DTL\GherkinLint\Model\Annotation\DisableRulesAnnotation;
use Generator;

class Linter
{
    private function __construct(
        private GherkinParser $parser,
        /**
         * @var Rule[]
         */
        private array $rules,
        private RuleConfigFactory $configFactory,
        private AnnotationParser $annotationParser,
    ) {
    }

    /**
     * @param Rule[] $rules
     */
    public static function create(RuleConfigFactory $configFactory, array $rules): self
    {
        return new self(new GherkinParser(), $rules, $configFactory, new AnnotationParser());
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    public function lint(string $uri, string $contents): Generator
    {
        foreach ($this->gherkinDocuments($uri, $contents) as $document) {
            $disableRules = [];
            foreach ($this->annotationParser->parseAll(array_map(fn (Comment $comment) => $comment->text, $document->comments)) as $annotation) {
                if ($annotation instanceof DisableRulesAnnotation) {
                    $disableRules = [...$disableRules, ...$annotation->disabledRules];
                }
            }

            foreach ($this->rules as $rule) {
                if (in_array($rule->describe()->name, $disableRules)) {
                    continue;
                }

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
