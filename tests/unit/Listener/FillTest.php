<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener;

use Rinsvent\IfModifiedSinceBundle\Exception\Idempotency\Key\Wrong;
use Rinsvent\IfModifiedSinceBundle\Tests\UnitTester;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class FillTest extends \Codeception\Test\Unit
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
    public function testSuccess()
    {
        $request = Request::create('/hello/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('X-idempotency-Key', '1234');
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Hello igor', $response->getContent());
        $response = $this->tester->send($request);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals('{}', $response->getContent());
    }

    public function testFail()
    {
        $request = Request::create('/hello/igor', 'GET', [
            'surname' => 'Surname'
        ]);
        $request->headers->set('X-idempotency-Key', '12341111111111111111111111111111111111111');

        $this->expectException(Wrong::class);
        $this->tester->send($request);
    }
}
