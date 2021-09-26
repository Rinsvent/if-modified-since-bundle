<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

interface TimeStampInterface
{
    public function get(TimeStampMeta $meta): int;
}