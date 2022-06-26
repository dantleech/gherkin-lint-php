<?php

namespace DTL\GherkinLint\Model;

use RuntimeException;

class RuleDescription
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        /**
         * @var class-string|null
         */
        public ?string $configClass = null,
        /**
         * @var RuleExample[]
         */
        public array $examples = [],
    ) {
    }

    public function newConfigClass(): RuleConfig
    {
        if (null === $this->configClass) {
            return new NullRuleConfig();
        }

        /** @psalm-suppress MixedMethodCall */
        $config = new $this->configClass;

        if (!$config instanceof RuleConfig) {
            throw new RuntimeException(sprintf(
                'Config class must be instanceof RuleConfig, got "%s"',
                get_class($config)
            ));
        }

        return $config;
    }
}
