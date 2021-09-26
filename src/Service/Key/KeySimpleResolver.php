<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

class KeySimpleResolver implements KeyResolverInterface
{
    public function resolve(KeyMeta $meta): KeyInterface
    {
        $metaClass = $meta::class;
        $grabberClass = $metaClass . 'Grabber';
        return new $grabberClass;
    }
}