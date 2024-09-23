<?php

namespace Marinha\Mvc\Services;

use Exception;
use Marinha\Mvc\Infra\Repository\LoginRepository;

class LoginServices extends SistemaServices
{

    public function __construct() {}

    public function login(array $usuario): ?array
    {
        try {
            $repository = new LoginRepository($this->Conexao());
            $retorno = $repository->login($usuario);
            if (count($retorno) == 1) {
                if ($retorno[0]["DataUltimoLogin"] != null) {
                    session_start();
                    session_regenerate_id(true);

                    $_SESSION['usuario'] = $retorno;

                    if (file_exists($this->diretorioLote .  $_SESSION['usuario'][0]["codusuario"])) {
                        $this->removeDirectory($this->diretorioLote . $_SESSION['usuario'][0]["codusuario"]);
                    }
                }

                $dadosList = array();

                array_push($dadosList, array(
                    'codoperacao' => "OP6",
                    'codusuario' => $retorno[0]["codusuario"],
                    'iddocumento' => 0,
                    'ipacesso' => $usuario[0]['ipusuario'],
                    'omusuario' => $retorno[0]["omusuario"],
                    'idperfil' => $retorno[0]["idacesso"],
                    'dataultimologin' => $retorno[0]["DataUltimoLogin"]
                ));

                if ($retorno[0]["DataUltimoLogin"] != null)
                    $this->gravarLogOperacoes($dadosList);

                return $dadosList;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    public function tentativaLogin($nip)
    {
        try {
            $repository = new LoginRepository($this->Conexao());
            $retorno = $repository->tentativaLogin($nip);
            return $retorno;
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    public function logout(array $dadosList): bool
    {
        try {
            session_start();
            $this->gravarLogOperacoes($dadosList);
            

            if (file_exists($this->diretorioLote .  $_SESSION['usuario'][0]["codusuario"])) {
                $this->removeDirectory($this->diretorioLote . $_SESSION['usuario'][0]["codusuario"]);
            }

            $_SESSION['usuario'] = null;
            session_destroy();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}