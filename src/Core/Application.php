<?php

namespace Illuminate\Core;

class Application
{
    public function __construct()
    {
        $request = new Request();
        echo $request->query('query1');
    }
}