<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use DTL\GherkinLint\Model\Annotation;
use DTL\GherkinLint\Model\AnnotationParser;
use DTL\GherkinLint\Model\Annotation\DisableRulesAnnotation;
use DTL\GherkinLint\Model\Exception\UnknownAnnotation;
use Generator;
use PHPUnit\Framework\TestCase;

class AnnotationParserTest extends TestCase
{
    /**
     * @dataProvider provideParseAnnotation
     */
    public function testParseAnnotation(string $comment, array $expected): void
    {
        self::assertEquals($expected, $this->parseComment($comment));
    }

    /**
     * @return Generator<string,array<int,DTL\GherkinLint\Model\Annotation\DisableRulesAnnotation>>
     */
    public static function provideParseAnnotation(): Generator
    {
        yield 'regular comment' => [
            '# just some comment',
            []
        ];
        yield 'disable rule' => [
            '# @gherkinlint-disable-rule foobar',
            [new DisableRulesAnnotation(['foobar'])],
        ];
        yield 'disable rules' => [
            '# @gherkinlint-disable-rule foobar,barfoo',
            [new DisableRulesAnnotation(['foobar', 'barfoo'])],
        ];
        yield 'disable rules with spaces' => [
            '# @gherkinlint-disable-rule foobar,barfoo,  barfog',
            [new DisableRulesAnnotation(['foobar', 'barfoo', 'barfog'])],
        ];
    }

    public function testParseUnknownAnnotation(): void
    {
        $this->expectException(UnknownAnnotation::class);
        $this->parseComment('# @gherkinlint-unknown');
    }

    /**
     * @return Annotation[]
     */
    private function parseComment(string $comment): array
    {
        return iterator_to_array((new AnnotationParser())->parseAll([$comment]), false);
    }
}
