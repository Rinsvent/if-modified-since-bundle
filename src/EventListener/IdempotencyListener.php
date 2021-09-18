<?php

namespace Rinsvent\IfModifiedSinceBundle\EventListener;

use Rinsvent\IfModifiedSinceBundle\Exception\Idempotency\Key\Wrong;
use Rinsvent\RedisManagerBundle\Exception\Lock;
use Rinsvent\RedisManagerBundle\Service\RedisHelperService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * todo добавить параметр на лимит ключей для контроля люъема данных
 * Как вариант переходить на мускул после этого, либо обрабатывать как очередь.
 * Выкидывать первого и добавлять в конец. Пострадает только время жизни ключа.
 */
class IdempotencyListener
{
    public const PREFIX = 'rinsvent:idempotency:';

    public function __construct(
        private
        RedisHelperService $redisHelperService,
        private int $ttl
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $idempotencyKey = $request->headers->get('X-Idempotency-Key');
        if (strlen($idempotencyKey) > 36) {
            throw new Wrong();
        }
        if (!$idempotencyKey) {
            return;
        }
        try {
            $this->redisHelperService->lock(self::PREFIX . $idempotencyKey, $this->ttl);
        } catch (Lock $e) {
            $event->setResponse(new JsonResponse(null, Response::HTTP_CONFLICT));
        }
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

    }
}
