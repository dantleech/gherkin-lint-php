<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use Closure;
use DTL\GherkinLint\Model\ConfigRule;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\FeatureFile;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Model\RuleConfigFactory;
use DTL\GherkinLint\Model\ConfigMapper;
use DTL\GherkinLint\Model\FeatureDiagnostic;
use DTL\GherkinLint\Model\FeatureDiagnosticSeverity;
use DTL\GherkinLint\Model\Linter;
use DTL\GherkinLint\Model\Range;
use DTL\GherkinLint\Rule\TestRule;
use Generator;
use PHPUnit\Framework\TestCase;

class LinterTest extends TestCase
{
    /**
     * @dataProvider provideLint
     */
    public function testLint(string $content, array $config, Closure $assertion): void
    {
        $rule1 = new TestRule('test1', [
            new FeatureDiagnostic(
                Range::fromInts(1, 1, 2, 2),
                FeatureDiagnosticSeverity::WARNING,
                'Foo'
            ),
        ]);
        $assertion($this->diagnostics($rule1, $config, $content));
    }

    /**
     * @return Generator<array{string}>
     */
    public static function provideLint(): Generator
    {
        yield [
            <<<'EOT'
                Feature: Foobar

                    Scenario: Foobar
                        Given this happened
                        When I do this
                        Then that should happen
                EOT
            ,
            [
                'test1' =>  new ConfigRule(true, [])
            ],
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
            }
        ];

        yield 'disabled rule' => [
            <<<'EOT'
                Feature: Foobar
                    Scenario: Foobar
                        Given this happened
                        When I do this
                        Then that should happen
                EOT
            ,
            [
                'test1' =>  new ConfigRule(false, [])
            ],
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];

        yield 'disabled rule via comment' => [
            <<<'EOT'
                # @gherkinlint-disable-rule test1
                Feature: Foobar
                    Scenario: Foobar
                        Given this happened
                        When I do this
                        Then that should happen
                EOT
            ,
            [
                'test1' =>  new ConfigRule(true, [])
            ],
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];
    }

    private function diagnostics(Rule $rule, array $config, string $content): FeatureDiagnostics
    {
        return new FeatureDiagnostics(new FeatureFile('fone', 'ftwo'), iterator_to_array((
            Linter::create(
                new RuleConfigFactory(ConfigMapper::create(), $config),
                [
                    $rule
                ],
            )
        )->lint('/path', $content)));
    }
}
