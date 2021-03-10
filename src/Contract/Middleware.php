<?php

namespace Src\Contract;

use Src\Core\Request;
use Src\Core\Response;

interface Middleware
{
    /**
     * Handling logic while middleware is called
     * 
     * @param \Src\Core\Request
     * @param \Src\Core\Response
     * @param callable $next
     * 
     * @return callable
     */
    public function handle(Request $request, Response $response, $next);
}