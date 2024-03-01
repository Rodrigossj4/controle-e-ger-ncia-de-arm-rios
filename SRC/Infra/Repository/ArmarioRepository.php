<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Armarios;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class ArmarioRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function listaArmarios(): array
    {
        try {
            $sqlQuery = "SELECT * FROM  {$this->schema}\"Armarios\";";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $armariosDataList = $stmt->fetchAll();
            $armariosList = array();
            foreach ($armariosDataList as $armariosData) {
                array_push($armariosList, array(
                    'id' => $armariosData['IdArmario'],
                    'codigo' => $armariosData['CodArmario'],
                    'nomeinterno' => $armariosData['NomeInterno'],
                    'nomeexterno' => $armariosData['NomeExterno']
                ));
            };

            return $armariosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarArmarios(array $armario): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"Armarios\"(CodArmario, NomeInterno, NomeExterno) values(?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($armario as $ar) {
                $armarioData = new Armarios(
                    null,
                    $ar['codigo'],
                    $ar['nomeinterno'],
                    $ar['nomeexterno'],
                );
            }

            $stmt->bindValue(1, $armarioData->codigo());
            $stmt->bindValue(2, $armarioData->nomeInterno());
            $stmt->bindValue(3, $armarioData->nomeExterno());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarArmarios(array $armario): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"Armarios\" SET CodArmario = ?, NomeInterno = ?, NomeExterno = ? WHERE IdArmario = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($armario as $ar) {
                $armarioData = new Armarios(
                    $ar['id'],
                    $ar['codigo'],
                    $ar['nomeinterno'],
                    $ar['nomeexterno'],
                );
            }

            $stmt->bindValue(1, $armarioData->codigo());
            $stmt->bindValue(2, $armarioData->nomeInterno());
            $stmt->bindValue(3, $armarioData->nomeExterno());
            $stmt->bindValue(4, $armarioData->id());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function excluirArmario(int $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"Armarios\" where IdArmario  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
