<?php

namespace DTL\GherkinLint\Tests\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class Workspace
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function putContents(string $path, string $contents): void
    {
        $path = $this->path($path);
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0744, true);
        }
        file_put_contents($path, $contents);
    }

    public function path(string $path): string
    {
        return Path::join($this->path, $path);
    }

    public function reset(): void
    {
        $fs = new Filesystem();
        $fs->remove($this->path);
        $fs->mkdir($this->path, 0777);
    }
}
