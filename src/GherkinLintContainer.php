<?php

namespace DTL\GherkinLint;

use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Command\LintCommand;
use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\RuleCollection;
use DTL\GherkinLint\Model\RuleConfigFactory;
use DTL\GherkinLint\Report\TableReport;
use DTL\GherkinLint\Report\TableReportRenderer;
use DTL\GherkinLint\Rule\AllowedTagsRule;
use DTL\GherkinLint\Rule\NoDuplicateTags;
use DTL\GherkinLint\Rule\NoEmptyFileRule;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

final class GherkinLintContainer
{
    public function __construct(private OutputInterface $output, private Config $config)
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
        ]);

        return $app;
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

    private function createRules(): RuleCollection
    {
        return new RuleCollection([
            new NoDuplicateTags(),
            new NoEmptyFileRule(),
            new AllowedTagsRule(),
        ], $this->config->enabledRules());
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
