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
        $startCol = $location->column ?? 1;
        return self::fromInts(
            $location->line,
            $startCol,
            $location->line,
            $startCol + mb_strlen($name)
        );
    }

    public static function fromByteOffsets(string $text, int $start, int $end): self
    {
        return new self(
            Position::fromOffset($text, $start),
            Position::fromOffset($text, $end)
        );
    }
}
