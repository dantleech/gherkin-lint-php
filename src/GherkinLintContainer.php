<?php

namespace DTL\GherkinLint;

use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Command\LintCommand;
use DTL\GherkinLint\Command\RuleDocumentationCommand;
use DTL\GherkinLint\Command\RulesCommand;
use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\RuleCollection;
use DTL\GherkinLint\Model\RuleConfigFactory;
use DTL\GherkinLint\Model\RuleDocumentationBuilder;
use DTL\GherkinLint\Report\TableReport;
use DTL\GherkinLint\Report\TableReportRenderer;
use DTL\GherkinLint\Rule\AllowedTagsRule;
use DTL\GherkinLint\Rule\FileNameRule;
use DTL\GherkinLint\Rule\IndentationRule;
use DTL\GherkinLint\Rule\KeywordOrderRule;
use DTL\GherkinLint\Rule\NoBackgroundWithSingleScenarioRule;
use DTL\GherkinLint\Rule\NoDuplicateTags;
use DTL\GherkinLint\Rule\NoDuplicatedFeatureNames;
use DTL\GherkinLint\Rule\NoDuplicatedScenarioNames;
use DTL\GherkinLint\Rule\NoEmptyBackgroundRule;
use DTL\GherkinLint\Rule\NoEmptyFileRule;
use DTL\GherkinLint\Rule\NoHomogenousTagsRule;
use DTL\GherkinLint\Rule\NoConsecutiveEmptyLinesRule;
use DTL\GherkinLint\Rule\ScenariosPerFileRule;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

final class GherkinLintContainer
{
    public function __construct(private OutputInterface $output, private Config $config, private bool $dev = false)
    {
    }

    public function application(): Application
    {
        $app = new Application('behatlint');
        $app->addCommands([
            new LintCommand(
                $this->createFinder((string)getcwd()),
                $this->createLinter(),
                $this->createReportRenderer(),
            ),
            new RulesCommand(
                $this->createConfigFactory(),
                $this->createRules(),
            ),
        ]);

        if ($this->dev) {
            $app->add(
                new RuleDocumentationCommand(
                    new RuleDocumentationBuilder($this->createRules()),
                )
            );
        }

        return $app;
    }

    public function createRules(): RuleCollection
    {
        return new RuleCollection([
            new AllowedTagsRule(),
            new FileNameRule(),
            new IndentationRule(),
            new KeywordOrderRule(),
            new NoBackgroundWithSingleScenarioRule(),
            new NoConsecutiveEmptyLinesRule(),
            new NoDuplicateTags(),
            new NoDuplicatedFeatureNames(),
            new NoDuplicatedScenarioNames(),
            new NoEmptyBackgroundRule(),
            new NoEmptyFileRule(),
            new NoHomogenousTagsRule(),
            new ScenariosPerFileRule(),
        ]);
    }

    private function createFinder(string $cwd): FeatureFinder
    {
        return new FeatureFinder($cwd);
    }

    private function createLinter(): Linter
    {
        return new Linter(
            new GherkinParser(
                includeSource: false,
            ),
            $this->createRules()->rules(),
            $this->createConfigFactory(),
        );
    }

    private function createReport(): TableReport
    {
        return new TableReport($this->output);
    }

    private function createConfigFactory(): RuleConfigFactory
    {
        return new RuleConfigFactory(ConfigMapper::create(), $this->config->rules);
    }

    private function createReportRenderer(): TableReportRenderer
    {
        return new TableReportRenderer($this->output);
    }
}
