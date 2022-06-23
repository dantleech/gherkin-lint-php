<?php

namespace DTL\GherkinLint\Model;

class Position
{
    public function __construct(
        public int $lineNo,
        public int $colNo
    ) {
    }

    public static function fromInts(int $startLine, int $startCol): self
    {
        return new self($startLine, $startCol);
    }
}
