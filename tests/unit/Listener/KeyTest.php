<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener;

use Rinsvent\IfModifiedSinceBundle\Service\TimeStampManager;
use Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures\KeyMeta;
use Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures\KeyMetaGrabber;
use Rinsvent\IfModifiedSinceBundle\Tests\UnitTester;
use Rinsvent\RedisManagerBundle\Service\RedisHelperService;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class KeyTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSuccessWithNotActualHeader()
    {
        $request = Request::create('/hello3/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('If-Modified-Since', (new \DateTime('-1 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
        $request->headers->set('If-Modified-Since', (new \DateTime('+1 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());

        KeyMetaGrabber::$userId = 2;
        $request->headers->set('If-Modified-Since', (new \DateTime('-1 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
        $request->headers->set('If-Modified-Since', (new \DateTime('+1 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }
}
