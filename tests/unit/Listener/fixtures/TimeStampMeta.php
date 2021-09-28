<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures;

use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampMeta as BaseTimeStampMeta;

#[\Attribute(\Attribute::TARGET_ALL|\Attribute::IS_REPEATABLE)]
class TimeStampMeta extends BaseTimeStampMeta
{

}
