<?php

namespace DTL\BehatLint;

use DTL\BehatLint\Command\LintCommand;
use Symfony\Component\Console\Application;

final class BehatLintContainer
{
    public function application(): Application
    {
        $app = new Application('behatlint');
        $app->addCommands([
            new LintCommand(),
        ]);

        return $app;
    }
}
