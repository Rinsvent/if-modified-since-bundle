<?php

namespace Rinsvent\IfModifiedSinceBundle\Tests\unit\Listener\fixtures;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function hello(Request $request)
    {
        return new Response(
            sprintf("Hello %s", $request->get('name'))
        );
    }

    #[TimeStampMeta()]
    public function hello2(Request $request)
    {
        return new Response(
            sprintf("Hello %s", $request->get('name'))
        );
    }

    #[KeyMeta()]
    public function hello3(Request $request)
    {
        return new Response(
            sprintf("Hello %s", $request->get('name'))
        );
    }
}
