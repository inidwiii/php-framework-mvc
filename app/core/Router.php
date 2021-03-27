<?php

namespace Gov\Core;

class Router
{
    private $controller;

    private $method;

    private $arguments = [];

    private $url;

    public function __construct(string $url)
    {
        $this->url = explode('/', trim($url, '/'));
    }

    public function dispatch()
    {
        $this->resolveController();
        $this->resolveMethod();
        $this->resolveArguments();

        return $this->resolve();
    }

    private function resolve()
    {
        echo call_user_func_array(
            [$this->controller, $this->method],
            $this->arguments
        );

        return;
    }

    private function resolveController(): void
    {
        $controller = array_shift($this->url) ?: 'Test';
        $controller = sprintf('%s%sController', 'Gov\\Controller\\', ucfirst($controller));

        if (class_exists($controller) !== false) {
            $this->controller = new $controller;
            return;
        }

        throw new \RuntimeException(
            sprintf("Can't resolve controller class with name: %s", $controller)
        );
    }

    private function resolveMethod(): void
    {
        if (!is_object($this->controller) && $this->controller === null) {
            throw new \RuntimeException(
                "Can't resolve the method since the controller is not resolved."
            );
        }

        $method = array_shift($this->url) ?: 'index';

        if (method_exists($this->controller, $method) !== false) {
            $this->method = $method;
            return;
        }

        throw new \RuntimeException(
            sprintf(
                "Can't resolve method name: %s at controller name: %s",
                $method,
                get_class($this->controller)
            )
        );
    }

    private function resolveArguments(): void
    {
        $reflector = new \ReflectionMethod($this->controller, $this->method);

        if (count($this->url) >= $reflector->getNumberOfParameters()) {
            $this->arguments = array_values($this->url);
            $this->url = [];
            return;
        }

        throw new \RuntimeException(
            "Can't resolve arguments since the given parameters less than the required parameters."
        );
    }
}
