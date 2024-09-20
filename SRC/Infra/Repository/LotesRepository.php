<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Lotes;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class LotesRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function listarLotes(): array
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"lotes\";";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $lotesDataList = $stmt->fetchAll();
            $lotesList = array();
            foreach ($lotesDataList as $lotesData) {
                array_push($lotesList, array(
                    'id' => $lotesData['id'],
                    'numeroLote' => $lotesData['NumeroLote'],
                    'pasta' => $lotesData['Pasta'],
                    'ativo' => $lotesData['Ativo']
                ));
            };
            
            return $lotesList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrar(array $lote): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"lotes\"(\"NumeroLote\", \"Pasta\", \"Ativo\") values(?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($lote as $lt) {
                $loteData = new Lotes(
                    null,
                    $lt['numeroLote'],
                    $lt['pasta'],
                    true
                );
            }

            $stmt->bindValue(1, $loteData->numeroLote());
            $stmt->bindValue(2, $loteData->pasta());
            $stmt->bindValue(3, $loteData->ativo());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}