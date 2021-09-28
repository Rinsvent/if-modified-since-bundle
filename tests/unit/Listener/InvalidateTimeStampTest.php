<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener;

use Rinsvent\IfModifiedSinceBundle\Service\TimeStampManager;
use Rinsvent\IfModifiedSinceBundle\Tests\UnitTester;
use Rinsvent\RedisManagerBundle\Service\RedisHelperService;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class InvalidateTimeStampTest extends \Codeception\Test\Unit
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
        /** @var RedisHelperService $rhs */
        $rhs = $this->tester->grabRedisHelperService();
        $rhs->set('rinsvent:ims:hello', (new \DateTime('-35 minutes'))->getTimestamp());

        /** @var TimeStampManager $tsm */
        $tsm = $this->tester->grabTimeStampManager();

        $request = Request::create('/hello/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('If-Modified-Since', (new \DateTime('-36 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
        $request->headers->set('If-Modified-Since', (new \DateTime('-34 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());

        $tsm->invalidate('hello');
        $request->headers->set('If-Modified-Since', (new \DateTime('-34 minutes'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
        $request->headers->set('If-Modified-Since', (new \DateTime())->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }
}
