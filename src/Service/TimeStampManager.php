<?php

namespace Rinsvent\IfModifiedSinceBundle\Service;

use Rinsvent\RedisManagerBundle\Service\RedisHelperService;

class TimeStampManager
{
    const PREFIX = 'rinsvent:ims:';

    public function __construct(
        private RedisHelperService $redisHelperService,
        private int                $ttl
    ) {}

    public function get(string $key): int
    {
        if (!$actualTimeStamp = $this->redisHelperService->get(self::PREFIX . $key)) {
            $actualTimeStamp = $this->getTimeStamp();
            $this->redisHelperService->set(self::PREFIX . $key, $actualTimeStamp, $this->ttl);
        }
        return $actualTimeStamp;
    }

    public function invalidate(string $key): void
    {
        $this->redisHelperService->set(self::PREFIX . $key, null);
    }

    private function getTimeStamp()
    {
        return time();
    }
}