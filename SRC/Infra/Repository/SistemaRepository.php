<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\LogOperacoes;

use Exception;
#implements IArmarioRepository
use PDO;

class SistemaRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function gravarLogOperacoes(array $log)
    {
        try {

            // var_dump($log);
            $sqlQuery = "INSERT INTO {$this->schema}\"Log\"(\"CodOperacao\", \"IdUsuario\", \"datahoraoperacao\", \"IdDocumento\", \"IpAcesso\") values(?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($log as $lg) {
                $logData = new LogOperacoes(
                    null,
                    $lg['codoperacao'],
                    (int)$lg['codusuario'],
                    Date("d/m/Y h:i:s"),
                    $lg['iddocumento'],
                    "",
                );
            }

            $stmt->bindValue(1, $logData->codoperacao());
            $stmt->bindValue(2, $logData->idUsuario());
            $stmt->bindValue(3, $logData->dh());
            $stmt->bindValue(4, $logData->idDocumento());
            $stmt->bindValue(5, $logData->ipAcesso());
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }
}
