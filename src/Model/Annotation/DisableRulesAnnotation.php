<?php

namespace DTL\GherkinLint\Model\Annotation;

use DTL\GherkinLint\Model\Annotation;

final class DisableRulesAnnotation implements Annotation
{
    public function __construct(
        /** @var string[] */
        public readonly array $disabledRules
    ) {
    }
}
