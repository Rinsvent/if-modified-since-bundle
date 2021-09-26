<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

class TimeStampSimpleResolver implements TimeStampResolverInterface
{
    public function resolve(TimeStampMeta $meta): TimeStampInterface
    {
        $metaClass = $meta::class;
        $grabberClass = $metaClass . 'Grabber';
        return new $grabberClass;
    }
}