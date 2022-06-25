<?php

namespace DTL\GherkinLint\Model;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\Mapper\Tree\Message\NodeMessage;
use CuyZ\Valinor\Mapper\Tree\Node;
use CuyZ\Valinor\Mapper\Tree\NodeTraverser;
use RuntimeException;

class ConfigMapper
{
    private TreeMapper $mapper;

    public function __construct(TreeMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $signature
     * @param mixed           $source
     *
     * @return T
     */
    public function map(string $signature, $source): object
    {
        try {
            return $this->mapper->map($signature, $source);
        } catch (MappingError $error) {
            $grouped = (new NodeTraverser(
                fn (Node $node) => [$node->name(), $node->messages()]
            ))->traverse($error->node());

            $errors = [];
            foreach ($grouped as [$name, $messages ]) {
                $errors = array_merge($errors, array_filter(array_map(function (NodeMessage $message) use ($name) {
                    if (!$message->isError()) {
                        return false;
                    }

                    return sprintf('Field `%s`: %s', $name, $message->__toString());
                }, $messages)));
            }

            throw new RuntimeException(sprintf(
                '%s: %s',
                rtrim($error->getMessage(), '.'),
                implode('", "', $errors)
            ));
        }
    }

    public static function create(): self
    {
        return new self((new MapperBuilder())->flexible()->mapper());
    }
}
