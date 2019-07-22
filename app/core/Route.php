<?php

namespace app\core;

use app\routes\RegexRule;

class Route extends Configurable
{
    public $method;
    public $path;
    public $controller;
    public $action;
    public $routeParams;

    public static function any($path, $controller, $action)
    {
        return Application::get()->addRoute('*', $path, $controller, $action);
    }

    public static function get($path, $controller, $action)
    {
        return Application::get()->addRoute('get', $path, $controller, $action);
    }

    public static function post($path, $controller, $action)
    {
        return Application::get()->addRoute('post', $path, $controller, $action);
    }

    public function match($path)
    {
        $matches = [];
        if (($this->path instanceof RegexRule && preg_match($this->path, $path, $matches)) || $this->path === $path) {
            array_shift($matches);
            $this->routeParams = $matches;
            return true;
        }

        return false;
    }
}
