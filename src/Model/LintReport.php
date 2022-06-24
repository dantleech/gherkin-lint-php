<?php

namespace DTL\GherkinLint\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<FeatureDiagnostics>
 */
class LintReport implements IteratorAggregate, Countable
{
    public function __construct(
        /**
         * @var FeatureDiagnostics[]
         */
        private array $featureDiagnosticsList,
        public readonly float $elapsedTime,
    ) {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->featureDiagnosticsList);
    }

    public function errorCount(): int
    {
        $count = 0;
        foreach ($this->featureDiagnosticsList as $featureDiagnostics) {
            $count += count($featureDiagnostics);
        }

        return $count;
    }

    public function hasErrors(): bool
    {
        foreach ($this->featureDiagnosticsList as $featureDiagnostics) {
            if (count($featureDiagnostics)) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        return count($this->featureDiagnosticsList);
    }
}
