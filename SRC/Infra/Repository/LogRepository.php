<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\LogOperacoes;
use Exception;
use PDO;

class LogRepository
{
    public $schema = "prodimagem.";
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrarLog(array $log): bool
    {
        try {
            $sqlQuery = "INSERT INTO {$this->schema}\"Log\"(idoperacao, idusuario, dh, idarmario) values(?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($log as $lg) {
                $logData = new LogOperacoes(
                    null,
                    $lg['idoperacao'],
                    $lg['idusuario'],
                    $lg['dh'],
                    $lg['idarmario']
                );
            }

            /* $stmt->bindValue(1, $logData-codO1());
            $stmt->bindValue(2, $logData->idUsuario());
            $stmt->bindValue(3, $logData->dh());
            $stmt->bindValue(4, $logData->idArmario());
            $stmt->execute();*/

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
