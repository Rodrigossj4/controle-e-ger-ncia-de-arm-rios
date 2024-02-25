<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\LoginServices;

class LoginController 
{
    public function index()
    {
       require __DIR__ . '../../Views/login/index.php';
    }

    public function login():bool
    {
        $login = array();
        array_push($login, array(
            'nip' => filter_input(INPUT_POST, 'nip'),
            'senhausuario' => filter_input(INPUT_POST, 'senha')
        ));

        $service = new LoginServices();
       
        //$dados = $service->login($login);  
        //?  header("location: /gerenciar-armarios") : require __DIR__ . '../../Views/login/index.php'
        $retorno = $service->login($login);
       
        var_dump($retorno);
        return $retorno ;
    }
}