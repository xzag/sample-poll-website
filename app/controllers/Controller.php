<?php

namespace app\controllers;

use app\core\Application;

abstract class Controller
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @return \app\core\Request
     */
    public function getRequest()
    {
        return $this->getApp()->getRequest();
    }

    public function view($template, $params = [])
    {
        return $this->getApp()->getRenderer()->render($template, $params);
    }

    public function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }
}
