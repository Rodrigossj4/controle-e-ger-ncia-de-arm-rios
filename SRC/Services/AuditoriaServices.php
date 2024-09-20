<?php

namespace Marinha\Mvc\Services;

use Exception;


use Marinha\Mvc\Infra\Repository\AuditoriaRepository;


class AuditoriaServices extends SistemaServices
{
    public function __construct()
    {
    }
    public function listaDadosAuditoria(): array
    {
        try {
            $repository = new AuditoriaRepository($this->Conexao());
            return $repository->listaDadosAuditoria();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function BuscarLogs($ListaLogs): array
    {
        $logsList = array();
        array_push($logsList, array(
            'operacao' => $ListaLogs->operacao,
            'nip' => $ListaLogs->nip,
            'data' => $ListaLogs->data,
            'ip' => $ListaLogs->ip
        ));

        try {
            $repository = new AuditoriaRepository($this->Conexao());
            return $repository->BuscarLogs($logsList);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function ListarOperacoes(): array
    {
        try {
            $repository = new AuditoriaRepository($this->Conexao());
            return $repository->ListarOperacoes();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
