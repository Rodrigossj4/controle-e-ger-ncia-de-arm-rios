<?php

namespace Marinha\Mvc\Services;

use Exception;
use Marinha\Mvc\Infra\Repository\LoginRepository;

class LoginServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function login(array $usuario): bool
    {
        try {
            $repository = new LoginRepository($this->Conexao());
            $retorno = $repository->login($usuario);

            if (count($retorno) == 1) {
                session_start();
                session_regenerate_id(true);
                $_SESSION['usuario'] = $retorno;

                $dadosList = array();

                array_push($dadosList, array(
                    'codoperacao' => "OP6",
                    'codusuario' => $retorno[0]["codusuario"],
                    'iddocumento' => 0,
                    'ipacesso' => $usuario[0]['ipusuario'],
                    'omusuario' => $retorno[0]["omusuario"],
                    'idperfil' => $retorno[0]["idacesso"]
                ));
                // var_dump($dadosList);
                $this->gravarLogOperacoes($dadosList);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function logout(array $dadosList): bool
    {
        try {
            $this->gravarLogOperacoes($dadosList);

            session_start();
            $_SESSION['usuario'] = null;
            session_destroy();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
