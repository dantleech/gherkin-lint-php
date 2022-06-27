<?php

namespace DTL\GherkinLint\Tests\Util;

class TestFeature
{
    public function __construct(
        public string $path,
        public string $content
    ) {
    }
}
