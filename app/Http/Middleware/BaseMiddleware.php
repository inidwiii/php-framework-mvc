<?php

namespace App\Http\Middleware;

use Src\Contract\Middleware;
use Src\Core\Request;
use Src\Core\Response;

abstract class BaseMiddleware implements Middleware
{
    abstract public function handle(Request $request, Response $response, $next);

    public function __invoke(Request $request, Response $response, $next)
    {
        return $this->handle($request, $response, $next);
    }
}