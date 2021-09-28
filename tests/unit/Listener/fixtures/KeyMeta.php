<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures;

use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyMeta as BaseKeyMeta;

#[\Attribute(\Attribute::TARGET_ALL|\Attribute::IS_REPEATABLE)]
class KeyMeta extends BaseKeyMeta
{

}
