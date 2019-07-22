<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

$app = \app\core\Application::get();
$app->run();
