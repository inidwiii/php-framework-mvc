<?php

namespace App\Http\Middleware;

use Src\Core\Request;
use Src\Core\Response;

class CSRFVerifyMiddleware extends BaseMiddleware
{
    public function handle(Request $request, Response $response, $next)
    {
        echo 'CSRF';
        return $next($request, $response);
    }
}