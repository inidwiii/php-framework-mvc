<?php

namespace Src\Core;

class Route
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    private $_current;

    private $_middlewares = [];

    private $_named = [];

    private $_request;

    private $_response;

    private $_routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->_middlewares = require realpath(PATH_APP_HTTP . 'kernel.php');
        $this->_request = $request;
        $this->_response = $response;
        $this->_routes = ['get' => [], 'post' => []];
    }

    public function get($url, array $callback)
    {
        $this->addRoute($url, $callback, self::METHOD_GET);
        return $this;
    }

    public function middleware(...$middlewares)
    {
        foreach ($middlewares as $middleware) {
            $current = &$this->_routes[$this->_request->method()][$this->_current]['middlewares'];
            if (in_array($middleware, $current)) continue;
            $current[] = $middleware;
        }
    }

    public function name($value)
    {
        $this->_named[$value] = $this->_current;
        return $this;
    }

    public function run()
    {
        $this->_current = null;

        foreach ($this->_routes[$this->_request->method()] as $route) {
            if (preg_match($route['regex'], $this->_request->path(), $params)) {
                $controller = $this->getRouteController($route);
                $method = $this->getRouteMethod($route);
                $params = array_slice($params, 1);
                $ref = $this->registerMiddlewares(new Middleware, $route['middlewares'])->run($this->_request, $this->_response);

                return call_user_func_array(
                    [new $controller, $method], 
                    array_merge([], $ref, $params)
                );
            }
        }
    }

    private function addRoute($url, array $callback, $method)
    {
        $this->_current = $url;
        $this->_routes[$method][$url] = [
            'url' => $this->_request->baseUrl($url), 
            'callback' => ['controller' => $callback[0], 'method' => $callback[1]],
            'middlewares' => ['before'],
            'regex' => $this->getRouteRegex($url),
        ];;
    }

    private function getMiddleware($middleware)
    {
        if (!(bool) array_key_exists($middleware, $this->_middlewares))
            throw new \InvalidArgumentException("Unable to get middleware at '{$middleware}'");
        return $this->_middlewares[$middleware];
    }

    private function getRouteController($route)
    {
        if (!(bool) array_key_exists('controller', $route['callback'])) 
            throw new \InvalidArgumentException("Unable to get controller at " . $route['url']);
        return $route['callback']['controller'];
    }

    private function getRouteMethod($route)
    {
        if (!(bool) array_key_exists('method', $route['callback'])) 
            throw new \InvalidArgumentException("Unable to get method at " . $route['url']);
        return $route['callback']['method'];
    }

    private function getRouteRegex($url)
    {
        $regex = rtrim($url, '/');
        $regex = preg_replace('/(\/\:\w+\?)/', '(?:/([^/]+?))?', $regex);
        $regex = preg_replace('/(\:\w+)/', '(?:([^/]+?))', $regex);
        $regex = str_replace('/', '\\/', $regex);
        $regex = "/^{$regex}\\/?$/i";

        return $regex;
    }

    private function registerMiddlewares(Middleware $handler, array $middlewares) 
    {
        foreach ($middlewares as $middleware) {
            $middleware = $this->getMiddleware($middleware);

            if (is_array($middleware)) {
                foreach ($middleware as $groupMember) $handler->register(new $groupMember);
                continue;
            }

            $handler->register(new $middleware);
        }

        return $handler;
    }
}