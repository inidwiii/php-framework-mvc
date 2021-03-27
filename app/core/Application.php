<?php

namespace Gov\Core;

class Application extends Container
{
    public function __construct(string $url, string $config, string $view)
    {
        parent::__construct();

        $this->singleton(Config::class)->with(compact('config'));
        $this->singleton(Cookie::class);
        $this->singleton(Database::class);
        $this->singleton(Router::class)->with(compact('url'));
        $this->singleton(Session::class);
        $this->singleton(View::class)->with(compact('view'));
    }

    public function boot()
    {
        $this->make(Router::class)->dispatch();
    }
}
