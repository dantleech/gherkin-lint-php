<?php

namespace DTL\GherkinLint\Model;

final class RuleConfigFactory
{
    /**
     * @param array<string,ConfigRule> $ruleConfig
     */
    public function __construct(private ConfigMapper $mapper, private array $ruleConfig)
    {
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
