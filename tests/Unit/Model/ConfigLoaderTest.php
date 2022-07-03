<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use DTL\GherkinLint\Model\Config;
use DTL\GherkinLint\Model\ConfigLoader;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Tests\LintTestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class ConfigLoaderTest extends LintTestCase
{
    public function testLoad(): void
    {
        $config = $this->loadConfig([
            'rules' => [
                'one' => [
                    'foo' => 'bar',
                ]
            ],
        ]);

        self::assertEquals(
            new Config(
                rules: ['one' => new ConfigRule(true, ['foo' => 'bar'])],
            ),
            $config
        );
        self::assertEquals(
            new ConfigRule(true, ['foo' => 'bar']),
            $config->rules['one'],
        );
    }

    public function testLoadWithDisabledRule(): void
    {
        $config = $this->loadConfig([
            'rules' => [
                'one' => [
                    'enabled' => false,
                    'foo' => 'bar',
                ]
            ],
        ]);

        self::assertEquals(
            new Config(
                rules: [
                    'one' => new ConfigRule(false, ['foo' => 'bar'])
                ]
            ),
            $config
        );
    }

    private function loadConfig(array $config): Config
    {
        $this->workspace()->reset();
        $this->workspace()->putContents('gherkinlint.json', json_encode($config));
        $output = new BufferedOutput();
        $loader = ConfigLoader::create($output, $this->workspace()->path('/'));
        $config = $loader->load('gherkinlint.json');
        return $config;
    }
}
