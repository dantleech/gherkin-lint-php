<?php

namespace DTL\GherkinLint\Model\Annotation;

final class DisableRulesAnnotation
{
    public function __construct(
        /** @var string[] */
        public readonly array $disabledRules
    ) {}
}
