<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

#[\Attribute(\Attribute::TARGET_ALL|\Attribute::IS_REPEATABLE)]
abstract class TimeStampMeta
{
    public const TYPE = 'simple';
}