<?php

namespace DTL\GherkinLint\Model;

use ArrayIterator;
use DTL\GherkinLint\Model\FeatureDiagnostics;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<FeatureDiagnostics>
 */
class LintReport implements IteratorAggregate
{
    public function __construct(
        /**
         * @var FeatureDiagnostics[]
         */
        private array $featureDiagnosticsList
    )
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->featureDiagnosticsList);
    }
}
