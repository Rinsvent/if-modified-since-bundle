<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

use Symfony\Component\DependencyInjection\ServiceLocator;

class KeyServiceResolver implements KeyResolverInterface
{
    public const TYPE = 'service';

    public function __construct(
        private ServiceLocator $grabberLocator
    ) {}

    public function resolve(KeyMeta $meta): KeyInterface
    {
        $grabberClass = $meta::class . 'Grabber';
        return $this->grabberLocator->get($grabberClass);
    }
}