<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

interface TimeStampResolverInterface
{
    public function resolve(TimeStampMeta $meta): TimeStampInterface;
}