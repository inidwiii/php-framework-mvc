<?php

namespace Illuminate\Core;

class Application
{
    public function __construct()
    {
        echo 'hello from ' . __METHOD__;
    }
}