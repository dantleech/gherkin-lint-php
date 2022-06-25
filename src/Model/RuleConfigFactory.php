<?php

namespace DTL\GherkinLint\Model;

final class RuleConfigFactory
{
    /**
     * @param array<string,mixed> $ruleConfig
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

        $config = $this->mapper->map(
            $configClass,
            $this->ruleConfig[$description->name] ?? []
        );

        assert($config instanceof RuleConfig);

        return $config;
    }
}
