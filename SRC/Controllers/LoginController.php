<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\LoginServices;
use Marinha\Mvc\Helpers\Helppers;

class LoginController
{
    public function index()
    {
        require __DIR__ . '../../Views/login/index.php';
    }

    public function login(): bool
    {
        $login = array();
        $funcoes = new Helppers();
        array_push($login, array(
            'nip' => $funcoes->somenteNumeros(filter_input(INPUT_POST, 'nip')),
            'senhausuario' => filter_input(INPUT_POST, 'senha')
        ));

        $service = new LoginServices();

        //$dados = $service->login($login);  
        //?  header("location: /gerenciar-armarios") : require __DIR__ . '../../Views/login/index.php'
        $retorno = $service->login($login);

        return $retorno;
    }

    public function logout()
    {
        $service = new LoginServices();
        $retorno = $service->logout();
        header("location: /");
    }
}
