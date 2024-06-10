<?php

namespace Marinha\Mvc\Infra\Repository;

use Exception;
use Marinha\Mvc\Infra\Repository\UsuarioRepository;
#implements IArmarioRepository
use PDO;

class AuditoriaRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function listaDadosAuditoria(): array
    {
        try {
            $sqlQuery = "SELECT \"IdLog\", \"CodOperacao\", \"IdUsuario\", \"datahoraoperacao\", \"IdDocumento\", \"IpAcesso\" FROM  {$this->schema}\"Log\" order by \"IdLog\" desc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $logDataList = $stmt->fetchAll();
            $logList = array();
            foreach ($logDataList as $logData) {
                array_push($logList, array(
                    'idlog' => $logData['IdLog'],
                    'codoperacao' => $this->RetornaDescricaoOperacao($logData['CodOperacao']),
                    'datahoraoperacao' => $logData['datahoraoperacao'],
                    'idusuario' => $this->RetornaNipUsuario($logData['IdUsuario']),
                    'iddocumento' => $logData['IdDocumento'],
                    'ipacesso' => $logData['IpAcesso']
                ));
            };

            return $logList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    private function RetornaDescricaoOperacao(string $codporacao): string
    {
        try {
            $sqlQuery = "SELECT \"DescOperacao\" FROM  {$this->schema}\"Operacoes\" WHERE \"CodOperacao\"  = ? ";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $codporacao);
            $stmt->execute();
            $armario = $stmt->fetchAll();

            return $armario[0]['DescOperacao'];
        } catch (Exception $e) {
            echo $e;
            return "";
        }
    }

    private function RetornaNipUsuario(string $idusuario): string
    {
        $repository = new UsuarioRepository($this->pdo);
        $retorno =  $repository->RetornaNipUsuario($idusuario);

        return  substr($retorno, 0, 2) . '.' . substr($retorno, 2, 4) . '.' . substr($retorno, 6, 8);
    }
}
