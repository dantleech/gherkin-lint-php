<?php

namespace DTL\GherkinLint;

use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Command\LintCommand;
use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDuplicateTags;
use DTL\GherkinLint\Rule\NoEmptyFileRule;
use Symfony\Component\Console\Application;

final class GherkinLintContainer
{
    public function application(): Application
    {
        $app = new Application('behatlint');
        $app->addCommands([
            new LintCommand(
                $this->createFinder((string)getcwd()),
                $this->createLinter(),
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
            $this->createRules()
        );
    }

    /**
     * @return list<Rule>
     */
    private function createRules(): array
    {
        return [
            new NoDuplicateTags(),
            new NoEmptyFileRule(),
        ];
    }
}
