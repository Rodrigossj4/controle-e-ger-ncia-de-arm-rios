<?php

namespace Marinha\Mvc\Services;

use Exception;


use Marinha\Mvc\Infra\Repository\ArmarioRepository;


class ArmarioServices extends SistemaServices
{
    public function __construct()
    {
    }
    public function listaArmarios(): array
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->listaArmarios();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function listaArmariosPorPerfil(int $idPerfil): array
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->listaArmariosPorPerfil($idPerfil);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }


    public function cadastrarArmarios(array $armario): bool
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->cadastrarArmarios($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function BuscarArmario(array $armario): int
    {
        $repository = new ArmarioRepository($this->Conexao());
        return $repository->BuscarArmario($armario);
    }

    public function vincularDocumentos(array $vinculosList): bool
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->vincularDocumentos($vinculosList);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function desvincularDocumentos(int $idDoc, int $idArmario): bool
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->desvincularDocumentos($idDoc, $idArmario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }


    public function alterarArmarios(array $armario): bool
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->alterarArmarios($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function excluirArmario(int $id): bool
    {
        try {
            $repository = new ArmarioRepository($this->Conexao());
            return $repository->inativarArmario($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
