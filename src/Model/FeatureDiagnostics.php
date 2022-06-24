<?php

namespace DTL\GherkinLint\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use Traversable;

/**
 * @implements IteratorAggregate<FeatureDiagnostic>
 */
class FeatureDiagnostics implements IteratorAggregate, Countable
{
    public function __construct(
        public readonly FeatureFile $file,
        /**
         * @var array<FeatureDiagnostic>
         */
        private array $featureDiagnostics
    ) {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->featureDiagnostics);
    }

    public function first(): FeatureDiagnostic
    {
        if (empty($this->featureDiagnostics)) {
            throw new RuntimeException(
                'There are no diagnostics'
            );
        }
        return $this->featureDiagnostics[array_key_first($this->featureDiagnostics)];
    }

    public function count(): int
    {
        return count($this->featureDiagnostics);
    }

    public function at(int $index): FeatureDiagnostic
    {
        if (!isset($this->featureDiagnostics[$index])) {
            throw new RuntimeException(
                'There is no diagnostic at index %d',
                $index
            );
        }
        return $this->featureDiagnostics[$index];
    }
}
