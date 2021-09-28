<?php
namespace Rinsvent\IfModifiedSinceBundle\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Predis\Client;
use Rinsvent\IfModifiedSinceBundle\EventListener\IfModifiedSinceListener;
use Rinsvent\IfModifiedSinceBundle\Service\Key\KeyServiceResolver;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStamp\TimeStampServiceResolver;
use Rinsvent\IfModifiedSinceBundle\Service\TimeStampManager;
use Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures\Controller;
use Rinsvent\RedisManagerBundle\Service\RedisHelperService;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Unit extends \Codeception\Module
{
    public function send(Request $request): Response
    {
        $routes = new RouteCollection();
        $controller = new Controller();
        $routes->add('hello', new Route('/hello/{name}', [
                '_controller' => [$controller, 'hello']
            ]
        ));
        $routes->add('hello2', new Route('/hello2/{name}', [
                '_controller' => [$controller, 'hello2']
            ]
        ));
        $routes->add('hello3', new Route('/hello3/{name}', [
                '_controller' => [$controller, 'hello3']
            ]
        ));

        $matcher = new UrlMatcher($routes, new RequestContext());
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        $listener = new IfModifiedSinceListener($this->grabTimeStampManager(), $this->grabKeyServiceResolver(), $this->grabTimeStampServiceResolver());
        $dispatcher->addListener('kernel.request', [$listener, 'onKernelRequest']);

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();
        $kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
        $response = $kernel->handle($request);
        $response->send();
        return $response;
    }

    public function grabClient()
    {
        return new Client('tcp://ifmodifiedsincebundle_redis:6379?password=password123');
    }

    public function grabRedisHelperService()
    {
        return new RedisHelperService($this->grabClient());
    }

    public function grabTimeStampManager()
    {
        return new TimeStampManager($this->grabRedisHelperService(), 1);
    }

    public function grabKeyServiceResolver()
    {
        $keyServiceLocator = new ServiceLocator([

        ]);
        return new KeyServiceResolver($keyServiceLocator);
    }

    public function grabTimeStampServiceResolver()
    {
        $timeStampServiceLocator = new ServiceLocator([

        ]);
        return new TimeStampServiceResolver($timeStampServiceLocator);
    }
}
