<?php

use app\core\Route;
use app\routes\RegexRule;

Route::get('/', \app\controllers\SiteController::class, 'index');
Route::any('/poll/create', \app\controllers\PollController::class, 'create');
Route::any(new RegexRule('/\/poll\/view\/([\w-]+)/'), \app\controllers\PollController::class, 'get');