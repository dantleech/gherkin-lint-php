<?php

namespace DTL\GherkinLint;

use Cucumber\Gherkin\GherkinParser;
use DTL\GherkinLint\Command\LintCommand;
use DTL\GherkinLint\Model\AstTraverser;
use DTL\GherkinLint\Model\FeatureFinder;
use DTL\GherkinLint\Model\Linter;
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
        return new Linter(new GherkinParser(), new AstTraverser([]));
    }
}
