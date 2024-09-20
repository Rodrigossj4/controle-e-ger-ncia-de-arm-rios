<?php

namespace Marinha\Mvc\Infra\Repository;

use DateTime;
use DateTimeZone;
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

            $sqlQuery = "INSERT INTO {$this->schema}\"Log\"(\"CodOperacao\", \"IdUsuario\", \"datahoraoperacao\", \"IdDocumento\", \"IpAcesso\") values(?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            $timezone = new DateTimeZone('America/Sao_Paulo'); // Substitua pelo fuso horário desejado

            // Criar um objeto DateTime com o fuso horário configurado
            $dateTime = new DateTime('now', $timezone);

            // Formatar a data no formato ISO 8601 para garantir compatibilidade com PostgreSQL
            $formattedDateTime = $dateTime->format('Y-m-d H:i:sP');
            foreach ($log as $lg) {
                $logData = new LogOperacoes(
                    null,
                    $lg['codoperacao'],
                    (int)$lg['codusuario'],
                    $formattedDateTime,
                    $lg['iddocumento'],
                    $lg['ipacesso'],
                );
            }

            $stmt->bindValue(1, $logData->codoperacao());
            $stmt->bindValue(2, $logData->idUsuario());
            $stmt->bindValue(3, $logData->datahoraoperacao());
            $stmt->bindValue(4, $logData->idDocumento());
            $stmt->bindValue(5, $logData->ipAcesso());
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }
}
