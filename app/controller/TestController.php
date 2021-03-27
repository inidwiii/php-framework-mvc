<?php

namespace Gov\Controller;

use Gov\Core\Controller;

class TestController extends Controller
{
    public function index()
    {
        return view('test', ['title' => config('app.name') . ' Page']);
    }
}
