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
}
