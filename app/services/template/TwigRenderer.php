<?php

namespace app\services\template;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigRenderer implements TemplateRenderer
{
    private $_twig;

    /**
     * TwigRenderer constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $loader = new FilesystemLoader($path);
        $this->_twig = new Environment($loader);
    }

    /**
     * @param $template
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($template, $params = [])
    {
        return $this->_twig->render($template, $params);
    }

    public function addParams($params)
    {
        foreach ($params as $key => $value) {
            $this->_twig->addGlobal($key, $value);
        }
    }
}
