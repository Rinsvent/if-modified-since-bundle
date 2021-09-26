<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

class KeyResolverStorage
{
    private array $items = [];

    public static function getInstance(): self
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        $instance = new self();
        $instance->add('simple', new KeySimpleResolver());

        return $instance;
    }

    public function add(string $code, KeyResolverInterface $transformerResolver): void
    {
        $this->items[$code] = $transformerResolver;
    }

    public function get(string $code): KeyResolverInterface
    {
        return $this->items[$code];
    }
}