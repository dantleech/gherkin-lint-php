<?php

namespace DTL\GherkinLint\Model;

use Cucumber\Messages\Location;

class Range
{
    public function __construct(
        public readonly Position $start,
        public readonly Position $end
    ) {
    }

    public static function fromInts(int $startLine, int $startCol, int $endLine, int $endCol): self
    {
        return new self(
            Position::fromInts($startLine, $startCol),
            Position::fromInts($endLine, $endCol)
        );
    }

    public static function fromLocationAndName(Location $location, string $name): self
    {
        return self::fromInts(
            $location->line,
            $location->column ?? 1,
            $location->line,
            mb_strlen($name)
        );
    }
}
