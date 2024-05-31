<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\OM;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class OMRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function listarOM(): array
    {
        try {
            $sqlQuery = "SELECT \"CodOM\", \"NomeAbreviado\", \"NomOM\" FROM  {$this->schema}\"OM\" where \"Ativa\" = B'1' order by \"CodOM\" asc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $OMDataList = $stmt->fetchAll();
            $OMList = array();
            foreach ($OMDataList as $OMData) {
                array_push($OMList, array(
                    'CodOM' => $OMData['CodOM'],
                    'NomeAbreviado' => $OMData['NomeAbreviado'],
                    'NomOM' => $OMData['NomOM']
                ));
            };

            return $OMList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarOM(array $om): bool
    {
        try {
            $sqlQuery = "INSERT INTO {$this->schema}\"OM\"(\"NomeAbreviado\", \"NomOM\", \"Ativa\") values(?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($om as $ar) {
                $omData = new OM(
                    null,
                    $ar['sigla'],
                    $ar['nomeOM'],
                    1
                );
            }

            $stmt->bindValue(1, $omData->nomeAbreviado());
            $stmt->bindValue(2, $omData->nomOM());
            $stmt->bindValue(3, $omData->ativa());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function ObterDadosOM(int $idOM)
    {
        try {
            $sqlQuery = "SELECT \"CodOM\", \"NomeAbreviado\", \"NomOM\" FROM  {$this->schema}\"OM\" where \"Ativa\" = B'1' AND \"CodOM\" = ? order by \"CodOM\" asc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idOM);
            $stmt->execute();

            $OMDataList = $stmt->fetchAll();
            $OMList = array();
            foreach ($OMDataList as $OMData) {
                array_push($OMList, array(
                    'CodOM' => $OMData['CodOM'],
                    'NomeAbreviado' => $OMData['NomeAbreviado'],
                    'NomOM' => $OMData['NomOM']
                ));
            };

            return $OMList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
