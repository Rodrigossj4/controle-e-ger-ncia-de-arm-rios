<?php

namespace Marinha\Mvc\Services;

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
}
