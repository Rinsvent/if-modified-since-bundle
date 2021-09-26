<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;

#[\Attribute(\Attribute::TARGET_ALL|\Attribute::IS_REPEATABLE)]
abstract class KeyMeta
{
    public const TYPE = 'simple';
}