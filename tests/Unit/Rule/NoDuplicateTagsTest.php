<?php

namespace DTL\GherkinLint\Tests\Unit\Rule;

use DTL\GherkinLint\Model\FeatureDiagnostics;
use DTL\GherkinLint\Model\Rule;
use DTL\GherkinLint\Rule\NoDuplicateTags;
use Generator;

class NoDuplicateTagsTest extends RuleTestCase
{
    public function createRule(): Rule
    {
        return new NoDuplicateTags();
    }

    public function provideTests(): Generator
    {
        yield 'feature with duplicate tags' => [
            <<<'EOT'
@foo @foo
Feature: Foobar
EOT
            , 
            function (FeatureDiagnostics $diagnostics) {
            }

        ];
    }
}
