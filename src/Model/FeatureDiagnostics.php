<?php

namespace DTL\BehatLint\Model;

use ArrayIterator;
use IteratorAggregate;
use PhpParser\Node\Expr\ArrayItem;
use Traversable;

/**
 * @implements IteratorAggregate<FeatureDiagnostic>
 */
class FeatureDiagnostics implements IteratorAggregate
{
    public function __construct(
        /**
         * @var array<FeatureDiagnostic>
         */
        private array $featureDiagnostics
    )
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->featureDiagnostics);
    }
}
