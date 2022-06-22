<?php

namespace DTL\BehatLint\Model;

use SplFileInfo;
use Symfony\Component\Filesystem\Path;

final class FeatureFile
{
    public function __construct(
        public string $path,
        public string $relativePath,
    )
    {
    }
}
