<?php

namespace Marinha\Mvc\Controllers;

class HomeController extends Controller
{
    public function index()
    {

        $this->validarSessao();
        require __DIR__ . '../../Views/Home.php';
    }
}
