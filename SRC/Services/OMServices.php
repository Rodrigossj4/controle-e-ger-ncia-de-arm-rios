<?php

namespace Marinha\Mvc\Services;

use Exception;


use Marinha\Mvc\Infra\Repository\OMRepository;


class OMServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function cadastrarOM(array $armario): bool
    {
        try {
            $repository = new OMRepository($this->Conexao());
            return $repository->cadastrarOM($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function listarOM(): array
    {
        try {
            $repository = new OMRepository($this->Conexao());
            return $repository->listarOM();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function ObterDadosOM(int $idOM){
        try {
            $repository = new OMRepository($this->Conexao());
            return $repository->ObterDadosOM($idOM);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
