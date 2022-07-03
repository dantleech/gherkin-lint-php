<?php

namespace DTL\GherkinLint\Model;

use DTL\Invoke\Invoke;

class ConfigMapper
{
    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    public function map(string $class, array $data): object
    {
        return Invoke::new($class, $data);
    }

    public static function create(): self
    {
        return new self(
        );
    }
}
