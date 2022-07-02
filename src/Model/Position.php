<?php

namespace DTL\GherkinLint\Model;

use DTL\GherkinLint\Util\StringUtil;
use RuntimeException;

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

    public static function fromOffset(string $text, int $offset): self
    {
        $lines = StringUtil::linesAndDelimiters($text);

        $textOffset = 0;
        $lineNo = 1;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            if ($offset >= $textOffset && $offset <= $textOffset + strlen($line)) {
                return new self($lineNo, mb_strlen(substr($line, 0, $offset - $textOffset)));
            }

            $newLine = $lines[++$i] ?? null;
            $lineNo++;
            $textOffset += strlen($line);
            if (null === $newLine) {
                break;
            }
            $textOffset += strlen($newLine);
        }

        throw new RuntimeException(sprintf(
            'Offset (%d) was out of range of document (length: %d)',
            $offset,
            $textOffset
        ));
    }
}
