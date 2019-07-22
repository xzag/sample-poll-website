<?php

namespace app\services\template;

interface TemplateRenderer
{
    public function render($template, $params = []);
    public function addParams($params);
}
