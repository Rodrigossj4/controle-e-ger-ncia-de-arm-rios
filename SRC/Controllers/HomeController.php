<?php

namespace Marinha\Mvc\Controllers;

class HomeController extends Controller
{
    public function index()
    {

        $this->validarSessao();
        //var_dump("Homes");
        require __DIR__ . '../../Views/Home.php';
    }
}
