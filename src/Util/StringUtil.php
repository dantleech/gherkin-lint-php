<?php

namespace DTL\GherkinLint\Util;

use RuntimeException;

final class StringUtil
{
    private const LINE_SPLIT_PATTERN = '{(\\r\\n|\\n|\\r)}';

    /**
     * @return string[]
     */
    public static function linesAndDelimiters(string $text): array
    {
        $lines = preg_split(self::LINE_SPLIT_PATTERN, $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (false === $lines) {
            throw new RuntimeException('Could not split lines');
        }
        return $lines;
    }

    /**
     * @return string[]
     */
    public static function lines(string $text): array
    {
        $lines = preg_split(self::LINE_SPLIT_PATTERN, $text);
        if (false === $lines) {
            throw new RuntimeException('Could not split lines');
        }
        return $lines;
    }
}
