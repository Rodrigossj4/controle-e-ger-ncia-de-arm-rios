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
            $sqlQuery = "SELECT * FROM  {$this->schema}\"Armarios\" where \"ativo\" = true order by \"NomeExterno\" asc;";
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

            $sqlQuery = "INSERT INTO {$this->schema}\"Armarios\"(\"CodArmario\", \"NomeInterno\", \"NomeExterno\") values(?, ?, ?);";
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

    public function vincularDocumentos(array $armario): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"ArmarioTipoDocumento\"(\"IdArmario\", \"IdTipoDoc\") values(?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);


            $stmt->bindValue(1, $armario[0]['id']);
            $stmt->bindValue(2, $armario[0]['idTipoDocumento']);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function desvincularDocumentos(int $idDoc, int $idArmario): bool
    {
        try {

            $sqlQuery = "Delete from {$this->schema}\"ArmarioTipoDocumento\" where \"IdArmario\" = ? and  \"IdTipoDoc\" = ? ;";
            $stmt = $this->pdo->prepare($sqlQuery);

            $stmt->bindValue(1, $idArmario);
            $stmt->bindValue(2, $idDoc);
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

            $sqlQuery = "UPDATE {$this->schema}\"Armarios\" SET \"CodArmario\" = ?, \"NomeInterno\" = ?, \"NomeExterno\" = ? WHERE \"IdArmario\" = ?";
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

    public function inativarArmario(int $id): bool
    {
        try {
            $sqlQuery = "update {$this->schema}\"Armarios\" set Ativo = false where \"IdArmario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
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
            $sqlQuery = "delete FROM {$this->schema}\"Armarios\" where \"IdArmario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function BuscarArmario(array $armario): int
    {
        $nomeExterno = $armario['0']["nomeexterno"];
        try {
            $sqlQuery = "SELECT * FROM  {$this->schema}\"Armarios\" WHERE LOWER(\"NomeExterno\") = LOWER('$nomeExterno');";
            //var_dump($sqlQuery);
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $armariosDataList = $stmt->fetchAll();
            return count($armariosDataList);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
