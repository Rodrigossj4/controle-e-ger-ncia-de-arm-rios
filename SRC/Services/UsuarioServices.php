<?php

namespace Marinha\Mvc\Services;

use Marinha\Mvc\Helpers\Helppers;

use Exception;

use Marinha\Mvc\Infra\Repository\UsuarioRepository;

class UsuarioServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function listaUsuarios(): array
    {
        try {
            $repository = new UsuarioRepository($this->Conexao());
            return $repository->listaUsuarios();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
    public function cadastrarUsuario(array $usuario)
    {
        try {
            $repository = new UsuarioRepository($this->Conexao());
            return $repository->cadastrarUsuario($usuario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarUsuario(array $armario): bool
    {
        try {
            $repository = new UsuarioRepository($this->Conexao());
            return $repository->alterarUsuario($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function excluirUsuario(int $id): bool
    {
        try {
            $repository = new UsuarioRepository($this->Conexao());
            return $repository->excluirUsuario($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function totalUsuariosPerfil(int $idPerfil): int
    {
        $repository = new UsuarioRepository($this->Conexao());
        return $repository->totalUsuariosPerfil($idPerfil);
    }

    public function validarNIP($nip): bool
    {
        $funcoes = new Helppers();
        $nip = $funcoes->somenteNumeros($nip);
        //var_dump($funcoes->validarNip($nip));
        return  $funcoes->validarNip($nip);
    }

    public function BuscarUsuarioNip($nip): int
    {
        $funcoes = new Helppers();
        $nip = $funcoes->somenteNumeros($nip);

        $repository = new UsuarioRepository($this->Conexao());
        return  $repository->BuscarUsuarioNip($nip);
    }
}
