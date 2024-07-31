<?php

namespace Marinha\Mvc\Services;

use Exception;

use Marinha\Mvc\Infra\Repository\PerfilAcessoRepository;


class PerfilAcessoServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function cadastrarPerfis(array $perfil): bool
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            $id  = $repository->cadastrarPerfis($perfil);
            $this->vincularPerfisArmario($perfil, $id);

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function BuscarPerfil(array $perfilList): int
    {
        $repository = new PerfilAcessoRepository($this->Conexao());
        return $repository->BuscarPerfil($perfilList);
    }

    public function listaPerfis(): array
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            return $repository->listaPerfis();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function exibirDadosPerfil(string $id): array
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            return $repository->exibirDadosPerfil($id);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterar(array $perfil, int $id): bool
    {
        try {

            $repository = new  PerfilAcessoRepository($this->Conexao());
            $repository->alterar($perfil);

            $this->removerVinculosPerfisArmario($id);
            $this->vincularPerfisArmario($perfil, $id);

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function excluir(int $id): bool
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            $this->removerVinculosPerfisArmario($id);
            return $repository->excluir($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    private function vincularPerfisArmario(array $perfil, int $idPerfil)
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            return $repository->vincularPerfisArmario($perfil, $idPerfil);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    private function removerVinculosPerfisArmario(int $idPerfil)
    {
        try {
            $repository = new PerfilAcessoRepository($this->Conexao());
            return $repository->removerVinculosPerfisArmario($idPerfil);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
