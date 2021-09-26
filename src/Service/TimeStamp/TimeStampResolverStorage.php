<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

class TimeStampResolverStorage
{
    private array $items = [];

    public static function getInstance(): self
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        $instance = new self();
        $instance->add('simple', new TimeStampSimpleResolver());

        return $instance;
    }

    public function add(string $code, TimeStampResolverInterface $transformerResolver): void
    {
        $this->items[$code] = $transformerResolver;
    }

    public function get(string $code): TimeStampResolverInterface
    {
        return $this->items[$code];
    }
}