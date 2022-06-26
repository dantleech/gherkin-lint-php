<?php

namespace DTL\GherkinLint\Command;

use DTL\GherkinLint\Model\RuleDocumentationBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuleDocumentationCommand extends Command
{
    public function __construct(
        private RuleDocumentationBuilder $builder,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('ruledocs');
        $this->setDescription('Generate documentation for rules');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path to generate rule documentation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string)$input->getArgument('path');
        file_put_contents($path, $this->builder->generate()."\n");
        return 0;
    }
}
