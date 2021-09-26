<?php

namespace Rinsvent\IfModifiedSinceBundle\EventListener;

use Cassandra\Time;
use Rinsvent\AttributeExtractor\MethodExtractor;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyInterface;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyResolverStorage;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyMeta;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyServiceResolver;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampMeta;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampResolverStorage;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampServiceResolver;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStampManager;
use Rinsvent\RedisManagerBundle\Service\RedisHelperService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * todo добавить параметр на лимит ключей для контроля люъема данных
 * Как вариант переходить на мускул после этого, либо обрабатывать как очередь.
 * Выкидывать первого и добавлять в конец. Пострадает только время жизни ключа.
 */
class IfModifiedSinceListener
{
    public const PREFIX = 'rinsvent:if-modified-since:';

    public function __construct(
        private TimeStampManager $timeStampManager,
        private KeyServiceResolver $keyServiceResolver,
        private TimeStampServiceResolver $timeStampServiceResolver
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->getMethod() !== Request::METHOD_GET) {
            return;
        }
        if (!$event->isMainRequest()) {
            return;
        }
        $ifModifiedSince = $request->headers->get('If-Modified-Since');
        if (!$ifModifiedSince) {
            return;
        }

        try {
            $expectedDateTime = new \DateTime($ifModifiedSince);
        } catch (\Throwable $e) {
            return;
        }

        if (!$routeKey = $request->get('_route')) {
            return;
        }

        if ($actualTimeStamp = $this->grabTimeStamp($request)) {
            $actualDateTime = (new \DateTime())->setTimestamp($actualTimeStamp);
            if ($expectedDateTime >= $actualDateTime) {
                $event->setResponse(new Response(null, Response::HTTP_NOT_MODIFIED));
            }
            return;
        }

        if (!$key = $this->grabKey($request)) {
            $key = $routeKey;
        }
        $actualTimeStamp = $this->timeStampManager->get($key);
        $actualDateTime = (new \DateTime())->setTimestamp($actualTimeStamp);
        if ($expectedDateTime >= $actualDateTime) {
            $event->setResponse(new Response(null, Response::HTTP_NOT_MODIFIED));
        }
    }

    private function grabController(Request $request): ?array
    {
        $controller = $request->get('_controller');
        if (is_string($controller)) {
            $controller = explode('::', $controller);
        }
        if (is_callable($controller)) {
            if (is_object($controller[0])) {
                $controller[0] = get_class($controller[0]);
            }
        }
        if (!is_array($controller) || !count($controller) === 2) {
            return null;
        }
        return $controller;
    }

    private function grabKeyMeta(Request $request): ?KeyMeta
    {
        $controller = $this->grabController($request);
        $methodExtractor = new MethodExtractor($controller[0], $controller[1]);
        /** @var KeyMeta|null $meta */
        $meta =  $methodExtractor->fetch(KeyMeta::class);

        return $meta;
    }

    protected function grabKey(Request $request): ?string
    {
        $storage = KeyResolverStorage::getInstance();
        $storage->add(KeyServiceResolver::TYPE, $this->keyServiceResolver);

        $meta = $this->grabKeyMeta($request);
        if (!$meta) {
            return null;
        }
        $storage = KeyResolverStorage::getInstance();
        $resolver = $storage->get($meta::TYPE);
        $keyGrabber = $resolver->resolve($meta);
        return $keyGrabber->get($meta);
    }

    private function grabTimeStampMeta(Request $request): ?TimeStampMeta
    {
        $controller = $this->grabController($request);
        $methodExtractor = new MethodExtractor($controller[0], $controller[1]);
        /** @var TimeStampMeta|null $meta */
        $meta =  $methodExtractor->fetch(TimeStampMeta::class);

        return $meta;
    }

    private function grabTimeStamp(Request $request): ?int
    {
        $storage = TimeStampResolverStorage::getInstance();
        $storage->add(TimeStampServiceResolver::TYPE, $this->timeStampServiceResolver);

        $meta = $this->grabTimeStampMeta($request);
        if (!$meta) {
            return null;
        }
        $resolver = $storage->get($meta::TYPE);
        $keyGrabber = $resolver->resolve($meta);
        return $keyGrabber->get($meta);
    }
}
