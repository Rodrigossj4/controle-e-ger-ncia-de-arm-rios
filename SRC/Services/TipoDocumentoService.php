<?php

namespace Marinha\Mvc\Services;

use Exception;
use Marinha\Mvc\Infra\Repository\TipoDocumentoRepository;

class TipoDocumentoService  extends SistemaServices
{
    public function __construct()
    {
    }

    public function cadastrarTipoDocumento(array $armario): bool
    {
        try {
            $repository = new TipoDocumentoRepository($this->Conexao());
            return $repository->cadastrarTipoDocumento($armario);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function listaTipoDocumento(): array
    {
        try {
            $repository = new TipoDocumentoRepository($this->Conexao());
            return $repository->listaTipoDocumento();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
    public function listaTipoDocumentoArmario(int $idArmario): array
    {

        try {
            $repository = new TipoDocumentoRepository($this->Conexao());
            return $repository->listaTipoDocumentoArmarios($idArmario);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterarTipoDoc(array $tipoDoc): bool
    {
        try {
            $repository = new TipoDocumentoRepository($this->Conexao());
            return $repository->alterarTipoDoc($tipoDoc);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluirTipoDocumento(int $id): bool
    {
        try {
            $repository = new TipoDocumentoRepository($this->Conexao());
            return $repository->excluirTipoDocumento($id);
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
