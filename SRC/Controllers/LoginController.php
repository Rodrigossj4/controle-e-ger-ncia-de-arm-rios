<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\LoginServices;
use Marinha\Mvc\Helpers\Helppers;

class LoginController extends Controller
{
    public function index()
    {
        require __DIR__ . '../../Views/login/index.php';
    }

    public function login()
    {
        $login = array();
        $funcoes = new Helppers();
        $nip = $funcoes->somenteNumeros(filter_input(INPUT_POST, 'nip'));

        array_push($login, array(
            'nip' => $nip,
            'senhausuario' => filter_input(INPUT_POST, 'senha'),
            'ipusuario' => $this->retornaIP()
        ));

        $service = new LoginServices();

        //$dados = $service->login($login);  
        //?  header("location: /gerenciar-armarios") : require __DIR__ . '../../Views/login/index.php'
        $retorno = $service->login($login);

        if($retorno == null){
            echo json_encode($service->tentativaLogin($nip));
        }else{
            echo json_encode($retorno);
        }
    }

    public function logout()
    {
        session_start();
        $dadosList = array();
        array_push($dadosList, array(
            'codoperacao' => "OP7",
            'codusuario' => $_SESSION['usuario'][0]["codusuario"],
            'iddocumento' => null,
            'ipacesso' => $this->retornaIP()
        ));

        $service = new LoginServices();
        $service->logout($dadosList);
        header("location: /");
    }
}