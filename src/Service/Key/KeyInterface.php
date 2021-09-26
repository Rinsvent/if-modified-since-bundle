<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

interface KeyInterface
{
    public function get(KeyMeta $meta): string;
}