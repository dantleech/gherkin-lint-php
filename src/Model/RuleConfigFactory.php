<?php

namespace DTL\GherkinLint\Model;

final class RuleConfigFactory
{
    public function __construct(
        private ConfigMapper $mapper,
        /**
         * @var array<string,ConfigRule>
         */
        private array $ruleConfig
    ) {
    }

    public function isEnabled(string $name): bool
    {
        $config = $this->ruleConfig[$name] ?? null;
        if (null === $config) {
            return true;
        }

        return $config->enabled;
    }

    public function for(RuleDescription $description): RuleConfig
    {
        $configClass = $description->configClass;

        if (null === $configClass) {
            return new NullRuleConfig();
        }
        $config = $this->ruleConfig[$description->name] ?? null;
        $config = $this->mapper->map(
            $configClass,
            $config->config ?? []
        );

        assert($config instanceof RuleConfig);

        return $config;
    }
}
