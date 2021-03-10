<?php

namespace App\Http\Middleware;

use Src\Core\Request;
use Src\Core\Response;

class FirstMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Response $response, $next)
    {
        echo 'first middleware<br>';
        return $next($request, $response);
    }
}