<?php

namespace DTL\GherkinLint\Command;

use DTL\GherkinLint\Model\RuleCollection;
use DTL\GherkinLint\Model\RuleConfigFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RulesCommand extends Command
{
    const ARG_PATH = 'path';

    public function __construct(
        private RuleConfigFactory $configFactory,
        private RuleCollection $rules
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('rules');
        $this->setDescription('Describe rules');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders([
            'name',
            'enabled',
            'description',
            'config'
        ]);
        $table->setColumnMaxWidth(2, 30);
        foreach ($this->rules->rules() as $rule) {
            $description = $rule->describe();
            $table->addRow([
                $description->name,
                $this->configFactory->isEnabled($description->name) ? '<bg=green;fg=black> YES </>' : '<bg=red;fg=white> NO  </>',
                $description->description,
                $description->configClass ? json_encode(
                    $this->configFactory->for($description),
                    JSON_PRETTY_PRINT
                ) : '{}',
            ]);
        }
        $table->render();

        return 0;
    }
}
