<?php
namespace Marinha\Mvc\Controllers;

class HomeController
{
    public function index(){

       //var_dump("Homes");
       require __DIR__ . '../Views/Home.php';
    }
}