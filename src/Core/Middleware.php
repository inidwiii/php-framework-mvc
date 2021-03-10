<?php

namespace Src\Core;

use Src\Contract\Middleware as MiddlewareInterface;

class Middleware
{
    public $_currentMiddleware;
    
    public function __construct()
    {
        $this->_currentMiddleware = function (Request $request, Response $response) {};
    }

    public function register(MiddlewareInterface $middleware)
    {
        $nextMiddleware = $this->_currentMiddleware;
        $this->_currentMiddleware = function (Request $request, Response $response) use ($middleware, $nextMiddleware) {
            return $middleware($request, $response, $nextMiddleware);
        };

        return $this;
    }

    public function run()
    {
        return call_user_func($this->_currentMiddleware, new Request(), new Response());
    }
}