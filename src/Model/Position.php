<?php

namespace DTL\GherkinLint\Model;

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
        $lines = preg_split("{(\r\n|\n|\r)}", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (false === $lines) {
            throw new RuntimeException(
                'Failed to preg-split text into lines'
            );
        }

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
            $offset, $textOffset
        ));
    }
}
