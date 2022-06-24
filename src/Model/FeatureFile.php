<?php

namespace DTL\GherkinLint\Model;

use RuntimeException;

final class FeatureFile
{
    public function __construct(
        public string $path,
        public string $relativePath,
    ) {
    }

    public function contents(): string
    {
        $contents = file_get_contents($this->path);
        if (false === $contents) {
            throw new RuntimeException(sprintf(
                'Could not read feature file "%s"',
                $this->path
            ));
        }
        return $contents;
    }
}
