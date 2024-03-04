<?php

namespace DTL\GherkinLint\Rule;

use Cucumber\Messages\Examples;
use Cucumber\Messages\FeatureChild;
use Cucumber\Messages\Location;
use Cucumber\Messages\Scenario;
use Cucumber\Messages\Step;
use Cucumber\Messages\TableRow;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\ParsedFeature;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfig;
use DTL\GherkinLint\Model\RuleDescription;
use DTL\GherkinLint\Model\RuleExample;
use Generator;

class IndentationRule implements Rule
{
    public function analyse(ParsedFeature $feature, RuleConfig $config): Generator
    {
        $document = $feature->document();
        assert($config instanceof IndentationConfig);

        $feature = $document->feature;

        if (null === $feature) {
            return;
        }

        yield from $this->check(
            $feature->location,
            $feature->keyword,
            $config,
            $config->feature
        );

        foreach ($feature->children as $child) {
            if (!$child instanceof FeatureChild) {
                continue;
            }
            if ($child->rule) {
                yield from $this->check($child->rule->location, $child->rule->keyword, $config, $config->rule);
            }
            if ($child->background) {
                yield from $this->check($child->background->location, $child->background->keyword, $config, $config->backgroud);
            }
            if ($child->scenario) {
                yield from $this->scnearioDiagnostics($child->scenario, $config);
            }
        }
    }

    public function describe(): RuleDescription
    {
        return new RuleDescription(
            'indentation',
            'Ensure consistent indentation',
            IndentationConfig::class,
            examples: [
                new RuleExample(
                    'Valid indentation',
                    valid: true,
                    example: <<<'EOT'
                        Feature: Foobar
                            Scenario: This is a scenario
                                Given this is a scenario
                                And the indentation is correct
                                When I run the linter
                                Then it should be fine
                        EOT,
                    config: new IndentationConfig(width: 4),
                ),
                new RuleExample(
                    'Invalid indentation',
                    valid: false,
                    example: <<<'EOT'
                         Feature: Foobar
                           Scenario: This is a scenario
                               Given this is a scenario
                               And the indentation is incorrect
                                When I run the linter
                               Then things will not be good
                        EOT,
                    config: new IndentationConfig(width: 4),
                )
            ]
        );
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function check(Location $location, string $name, IndentationConfig $config, int $expectedLevel): Generator
    {
        $expectedLevel = $expectedLevel * $config->width;

        $column = $location->column;

        if ($column === null) {
            return;
        }

        if ($column -1 === $expectedLevel) {
            return;
        }

        yield new FeatureDiagnostic(
            Range::fromLocationAndName($location, $name),
            FeatureDiagnosticSeverity::WARNING,
            sprintf('Expected indentation level on "%s" to be %d but got %d', $name, $expectedLevel, $column - 1)
        );
    }

    private function scnearioDiagnostics(Scenario $scenario, IndentationConfig $config): Generator
    {
        yield from $this->check($scenario->location, $scenario->keyword, $config, $config->backgroud);
        
        foreach ($scenario->steps as $step) {
            yield from $this->stepDiagnostics($step, $config);
        }

        foreach ($scenario->examples as $example) {
            yield from $this->exampleDiagnostics($example, $config);
        }
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function stepDiagnostics(Step $step, IndentationConfig $config): Generator
    {
        yield from $this->check($step->location, $step->keyword, $config, $config->step);

        if ($step->dataTable) {
            foreach ($step->dataTable->rows as $row) {
                yield from $this->tableRowDiagnostics($row, $config, $config->table);
            }
        }
        if ($step->docString) {
            yield from $this->check($step->docString->location, $step->docString->delimiter, $config, $config->literalBlock);
        }
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function exampleDiagnostics(Examples $example, IndentationConfig $config): Generator
    {
        yield from $this->check($example->location, $example->keyword, $config, $config->step);

        yield from $this->tableRowDiagnostics($example->tableHeader, $config, $config->examplesTable);
    }

    /**
     * @return Generator<FeatureDiagnostic>
     */
    private function tableRowDiagnostics(?TableRow $tableRow, IndentationConfig $config, int $level): Generator
    {
        if (null === $tableRow) {
            return;
        }
        yield from $this->check(
            $tableRow->location,
            'table row',
            $config,
            $level
        );
    }
}
