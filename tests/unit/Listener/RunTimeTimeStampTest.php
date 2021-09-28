<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener;

use Rinsvent\IfModifiedSinceBundle\Tests\UnitTester;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class RunTimeTimeStampTest extends \Codeception\Test\Unit
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
        $request = Request::create('/hello2/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('If-Modified-Since', (new \DateTime('-6 hours'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
    }

    public function testSuccessWithActualHeader()
    {
        $request = Request::create('/hello2/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('If-Modified-Since', (new \DateTime('-4 hours'))->format(\DateTimeInterface::RFC7231));
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }
}
