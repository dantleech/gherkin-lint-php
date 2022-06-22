<?php

namespace DTL\BehatLint\Model;

class Position
{
    public function __construct(
        public int $lineNo,
        public int $colNo
    ) {
    }
}
