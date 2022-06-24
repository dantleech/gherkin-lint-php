<?php

namespace DTL\GherkinLint\Model;

use Generator;
use RuntimeException;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class FeatureFinder
{
    public function __construct(private string $cwd)
    {
    }

    /**
     * @return Generator<FeatureFile>
     */
    public function find(string $path): Generator
    {
        $path = $this->makeAbsolute($path);

        if (!file_exists($path)) {
            throw new RuntimeException(sprintf(
                'Provided path "%s" does not exist',
                $path
            ));
        }

        if (is_file($path)) {
            yield new FeatureFile(
                $path,
                Path::makeRelative($path, $this->cwd)
            );
            return;
        }

        $finder = new Finder();
        $finder->in($path)->name('*.feature');

        foreach ($finder as $info) {
            if (!$info instanceof SplFileInfo) {
                continue;
            }
            yield new FeatureFile(
                $info->getPathname(),
                Path::makeRelative($info->getPathname(), $this->cwd)
            );
        }
    }

    private function makeAbsolute(string $path): string
    {
        return Path::makeAbsolute($path, $this->cwd);
    }
}
