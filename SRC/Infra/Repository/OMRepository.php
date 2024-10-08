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
            $sqlQuery = "INSERT INTO {$this->schema}\"OM\"( \"CodOM\", \"NomeAbreviado\", \"NomOM\", \"Ativa\") values(?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($om as $ar) {
                $omData = new OM(
                    $ar['codOM'],
                    $ar['sigla'],
                    $ar['nomeOM'],
                    1
                );
            }

            $stmt->bindValue(1, $omData->codOM());
            $stmt->bindValue(2, $omData->nomeAbreviado());
            $stmt->bindValue(3, $omData->nomOM());
            $stmt->bindValue(4, $omData->ativa());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function atualizarOM(array $om): bool
    {
        try {
            $sqlQuery = "UPDATE {$this->schema}\"OM\" SET \"NomeAbreviado\" = ?, \"NomOM\" = ?, \"Ativa\" = ? WHERE \"CodOM\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($om as $td) {
                $omData = new OM(
                    $td['codOM'],
                    $td['sigla'],
                    $td['nomeOM'],
                    1,
                );
            }
            $stmt->bindValue(1, $omData->nomeAbreviado());
            $stmt->bindValue(2, $omData->nomOM());
            $stmt->bindValue(3, $omData->ativa());
            $stmt->bindValue(4, $omData->codOM());
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function ObterDadosOM(string $idOM)
    {
        try {
            $sqlQuery = "SELECT \"CodOM\", \"NomeAbreviado\", \"NomOM\" FROM  {$this->schema}\"OM\" where \"Ativa\" = B'1' AND \"CodOM\" = ? order by \"CodOM\" asc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, (int)$idOM);
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

    public function obterUsersOM(string $idOM)
    {
        try {
            $sqlQuery = "SELECT \"OMUsuario\" FROM  {$this->schema}\"Usuarios\" where \"OMUsuario\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, (int)$idOM);
            $stmt->execute();

            $OMDataList = $stmt->fetchAll();
            $OMList = array();
            foreach ($OMDataList as $OMData) {
                array_push($OMList, array(
                    'OMUsuario' => $OMData['OMUsuario']
                ));
            };

            return $OMList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }    

    public function excluirOM(int $idOM): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"OM\" where \"CodOM\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idOM);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
}
