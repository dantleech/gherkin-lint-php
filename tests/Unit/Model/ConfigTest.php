<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\ConfigRule;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testEnabledRules(): void
    {
        $config = new Config(
            rules: [
                'foobar' => new ConfigRule(false, []),
                'barfoo' => new ConfigRule(true, []),
            ]
        );

        self::assertEquals(['barfoo'], $config->enabledRules());
    }
}
