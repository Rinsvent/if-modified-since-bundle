<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures;

use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyAbstractGrabber;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyMeta;

class KeyMetaGrabber extends KeyAbstractGrabber
{
    public static int $userId = 1;

    public function get(KeyMeta $meta): string
    {
        $userId = self::$userId;
        return "hello:{$userId}";
    }
}
