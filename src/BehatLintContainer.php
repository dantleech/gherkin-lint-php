<?php

namespace DTL\BehatLint;

use DTL\BehatLint\Command\LintCommand;
use DTL\BehatLint\Model\FeatureFinder;
use DTL\BehatLint\Model\Linter;
use Symfony\Component\Console\Application;

final class BehatLintContainer
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
        return new Linter();
    }
}
