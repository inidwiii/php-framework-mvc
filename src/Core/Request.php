<?php

namespace Illuminate\Core;

class Request
{
    private $_host;
    private $_method;
    private $_path;
    private $_port;
    private $_scheme;
    
    private $_input = [];
    private $_query = [];

    public function __construct()
    {
        $this->_host    = $_SERVER['SERVER_NAME'];
        $this->_method  = $_SERVER['REQUEST_METHOD'];
        $this->_path    = $_SERVER['REQUEST_URI'];
        $this->_port    = $_SERVER['SERVER_PORT'];
        $this->_scheme  = $_SERVER['REQUEST_SCHEME'];
        $this->_input   = $_POST;
        $this->_query   = empty($_GET) ? [] : array_slice($_GET, 1);
    }

    public function input($key = null, $default = null)
    {
        if (is_null($key)) return $this->_input;
        if (!(bool) array_key_exists($key, $this->_input)) return $default;
        return htmlspecialchars(filter_var($this->_input[$key], FILTER_SANITIZE_STRING));
    }

    public function method()
    {
        return mb_convert_case($this->_method, MB_CASE_LOWER, 'UTF-8');
    }

    public function path()
    {
        return $this->_path;
    }

    public function port()
    {
        return $this->_port;
    }

    public function query($key = null, $default = null)
    {
        if (is_null($key)) return $this->_query;
        if (!(bool) array_key_exists($key, $this->_query)) return $default;
        return htmlspecialchars(filter_var($this->_query[$key], FILTER_SANITIZE_STRING));
    }

    public function url()
    {
        return "{$this->_scheme}://{$this->_host}{$this->_path}";
    }
}