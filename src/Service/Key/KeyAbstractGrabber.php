<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\Key;


abstract class KeyAbstractGrabber implements KeyInterface
{
    public static function getLocatorKey()
    {
        return static::class;
    }
}
