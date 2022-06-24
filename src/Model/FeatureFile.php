<?php

namespace DTL\GherkinLint\Model;

final class FeatureFile
{
    public function __construct(
        public string $path,
        public string $relativePath,
    ) {
    }
}
