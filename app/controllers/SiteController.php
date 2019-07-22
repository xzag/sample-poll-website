<?php

namespace app\controllers;

class SiteController extends Controller
{
    public function index()
    {
        return $this->redirect('/poll/create');
    }
}
