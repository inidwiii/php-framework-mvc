<?php

namespace Src\Core;

class Response
{
    private $_code;

    public function json($response)
    {
        return json_encode($response);
    }

    public function redirect($url)
    {
        return header("Location: {$url}", true, $this->status(301));
    }

    public function status($code = null)
    {
        if (!is_null($code)) $this->_code = (int) $code;
        return $this->_code;
    }
}