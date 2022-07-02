<?php

namespace DTL\GherkinLint\Model;

use DTL\GherkinLint\Model\Annotation\DisableRulesAnnotation;
use DTL\GherkinLint\Model\Exception\UnknownAnnotation;
use Generator;

final class AnnotationParser
{
    /**
     * @return Generator<Annotation>
     * @param string[] $comments
     */
    public function parseAll(array $comments): Generator
    {
        foreach ($comments as $comment) {
            yield from $this->parse($comment);
        }
    }

    /**
     * @return Generator<Annotation>
     */
    private function parse(string $comment): Generator
    {
        if (!preg_match('{#\s*@gherkinlint-([a-z-]+)( .*?)?$}', $comment, $matches)) {
            return;
        }

        $command = $matches[1];
        $args = $matches[2] ?? '';

        $annotation = match ($command) {
            'disable-rule' => new DisableRulesAnnotation(
                array_map('trim', explode(',', $args))
            ),
            default => throw new UnknownAnnotation(sprintf(
                'Unknown annotation gherkinlint-%s',
                $command
            ))
        };

        yield $annotation;
    }
}
