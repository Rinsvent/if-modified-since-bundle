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
}
