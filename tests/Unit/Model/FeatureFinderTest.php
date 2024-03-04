<?php

namespace DTL\GherkinLint\Tests\Unit\Model;

use DTL\GherkinLint\Model\FeatureFile;
use DTL\GherkinLint\Model\FeatureFinder;
use Generator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FeatureFinderTest extends TestCase
{
    public function testThrowExceptionIfPathNotExisting(): void
    {
        $this->expectException(RuntimeException::class);
        iterator_to_array($this->createFinder(__DIR__)->find(__DIR__ . '/not-existing'));
    }

    /**
     * @dataProvider provideFindFeatures
     * @param array<string> $expectedPaths
     */
    public function testFindFeatures(string $path, array $expectedPaths): void
    {
        $expectedPaths = sort($expectedPaths);
        $actualPaths = array_map(
            fn (FeatureFile $file) => $file->relativePath,
            iterator_to_array($this->createFinder(__DIR__)->find($path))
        );
        $actualPaths = sort($actualPaths);
        self::assertEquals(
            $expectedPaths,
            $actualPaths,
        );
    }

    /**
     * @return Generator<array{string,array<int,string>}>
     */
    public static function provideFindFeatures(): Generator
    {
        yield 'relative' => [
            'feature-finder/relative',
            [
                'feature-finder/relative/foo.feature'
            ],
        ];

        yield 'single file' => [
            'feature-finder/relative/foo.feature',
            [
                'feature-finder/relative/foo.feature'
            ],
        ];

        yield 'many at different levels' => [
            'feature-finder/many',
            [
                'feature-finder/many/domain1/bar.feature',
                'feature-finder/many/domain1/foo.feature',
                'feature-finder/many/domain2/subdomain1/foo.feature',
                'feature-finder/many/domain2/foo.feature',
            ],
        ];
    }

    private function createFinder(string $cwd): FeatureFinder
    {
        return (new FeatureFinder($cwd));
    }
}
