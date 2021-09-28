<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures;

use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampAbstractGrabber;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampMeta as BaseTimeStampMeta;

class TimeStampMetaGrabber extends TimeStampAbstractGrabber
{
    public function get(BaseTimeStampMeta $meta): int
    {
        return (new \DateTime('-5 hours'))->getTimestamp();
    }
}
