<?php

namespace Rinsvent\IfModifiedSinceBundle\Service\TimeStamp;

use Symfony\Component\DependencyInjection\ServiceLocator;

class TimeStampServiceResolver implements TimeStampResolverInterface
{
    public const TYPE = 'service';

    public function __construct(
        private ServiceLocator $grabberLocator
    ) {}

    public function resolve(TimeStampMeta $meta): TimeStampInterface
    {
        $grabberClass = $meta::class . 'Grabber';
        return $this->grabberLocator->get($grabberClass);
    }
}