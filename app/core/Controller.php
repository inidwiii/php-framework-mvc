<?php

namespace Gov\Core;

class Controller
{
    private $model;

    public function __construct()
    {
        $this->model = app()->resolve(Model::class);
    }

    protected function model(string $name)
    {
        return $this->model->make($name);
    }
}
