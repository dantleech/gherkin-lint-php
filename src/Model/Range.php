<?php

namespace DTL\GherkinLint\Model;

use function PHPUnit\Framework\identicalTo;

class Range
{
    public function __construct(
        public readonly Position $start,
        public readonly Position $end
    )
    {
    }

    public static function fromInts(int $startLine, int $startCol, int $endLine, int $endCol): self
    {
        return new self(
            Position::fromInts($startLine, $startCol),
            Position::fromInts($endLine, $endCol)
        );
    }
}
