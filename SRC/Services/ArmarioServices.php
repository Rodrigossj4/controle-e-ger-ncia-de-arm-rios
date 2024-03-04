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
            return $repository->excluirArmario($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
