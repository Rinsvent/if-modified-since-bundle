<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

interface KeyResolverInterface
{
    public function resolve(KeyMeta $meta): KeyInterface;
}