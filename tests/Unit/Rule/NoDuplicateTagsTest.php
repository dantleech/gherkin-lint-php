<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDuplicateTags;
use DTL\GherkinLint\Tests\Util\TestFeature;
use Generator;

class NoDuplicateTagsTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDuplicateTags();
    }

    public static function provideTests(): Generator
    {
        yield 'feature with no duplicate tags' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    @foo @bar
                    Feature: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(0, $diagnostics);
            }
        ];

        yield 'feature with duplicate tags' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    @foo @foo
                    Feature: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals(1, $diagnostics->first()->range->start->lineNo);
                self::assertEquals(6, $diagnostics->first()->range->start->colNo);
                self::assertEquals(1, $diagnostics->first()->range->end->lineNo);
                self::assertEquals(10, $diagnostics->first()->range->end->colNo);
                self::assertEquals('Tag "@foo" is a duplicate', $diagnostics->first()->message);
            }
        ];

        yield 'scneario with duplicate tags' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    Feature: Foobar
                        @foo @foo
                        Scenario: Foo
                            When this then that
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(1, $diagnostics);
                self::assertEquals(2, $diagnostics->first()->range->start->lineNo);
                self::assertEquals(10, $diagnostics->first()->range->start->colNo);
                self::assertEquals('Tag "@foo" is a duplicate', $diagnostics->first()->message);
            }
        ];

        yield 'feature with many duplicate tags' => [
            new TestFeature(
                'foo.feature',
                <<<'EOT'
                    @foo @foo @baz @baz @foo
                    Feature: Foobar
                    EOT
            ),
            function (FeatureDiagnostics $diagnostics): void {
                self::assertCount(3, $diagnostics);
                self::assertEquals('Tag "@foo" is a duplicate', $diagnostics->at(0)->message);
                self::assertEquals('Tag "@baz" is a duplicate', $diagnostics->at(1)->message);
                self::assertEquals('Tag "@foo" is a duplicate', $diagnostics->at(2)->message);
            }
        ];
    }
}
