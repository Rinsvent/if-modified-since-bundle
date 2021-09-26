<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;


abstract class TimeStampAbstractGrabber implements TimeStampInterface
{
    public static function getLocatorKey()
    {
        return static::class;
    }
}
