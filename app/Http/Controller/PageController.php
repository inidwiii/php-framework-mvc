<?php

namespace App\Http\Controller;

use Src\Core\Request;
use Src\Core\Response;

class PageController
{
    public function index(Request $request, Response $response,)
    {
        echo 'hello, ' . $response->status();
    }
}