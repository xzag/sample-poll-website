<?php

namespace app\core;

class Request
{
    private $_data;

    public function __construct($data = [])
    {
        $this->_data = $data;
    }

    public function get($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    public function post($key = null)
    {
        if (!isset($key)) {
            return $_POST;
        }

        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    public function getMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
    }

    public function getPath()
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        return parse_url($requestUri, PHP_URL_PATH);
    }

    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }
}
