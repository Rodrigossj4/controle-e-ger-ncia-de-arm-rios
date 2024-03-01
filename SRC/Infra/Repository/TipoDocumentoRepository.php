<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\TipoDocumento;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class TipoDocumentoRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrarTipoDocumento(array $tipoDocumento): bool
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"TipoDocumento\"(DescTipoDoc) values(?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($tipoDocumento as $dc) {
                $tipoDocumentoData = new TipoDocumento(
                    null,
                    $dc['DescTipoDoc'],
                    $dc['armario']
                );
            }

            $stmt->bindValue(1, $tipoDocumentoData->descTipo());
            // $stmt->bindValue(2, $tipoDocumentoData->armario());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function listaTipoDocumento(): array
    {
        try {
            $sqlQuery = "SELECT * FROM  {$this->schema}\"TipoDocumento\";";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $tipodocumentoDataList = $stmt->fetchAll();
            $tipodocumentoList = array();
            foreach ($tipodocumentoDataList as $tipodocumentoData) {
                array_push($tipodocumentoList, array(
                    'id' => $tipodocumentoData['IdTipoDoc'],
                    'desctipo' => $tipodocumentoData['DescTipoDoc']
                    //,'armario' => $tipodocumentoData['armario']
                ));
            };

            return $tipodocumentoList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function listaTipoDocumentoArmarios(int $idArmario): array
    {
        try {
            $sqlQuery = "SELECT * FROM  {$this->schema}\"TipoDocumento\";";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idArmario);
            $stmt->execute();

            $tipodocumentoDataList = $stmt->fetchAll();
            $tipodocumentoList = array();
            foreach ($tipodocumentoDataList as $tipodocumentoData) {
                array_push($tipodocumentoList, array(
                    'id' => $tipodocumentoData['IdTipoDoc'],
                    'desctipo' => $tipodocumentoData['DescTipoDoc']
                ));
            };

            return $tipodocumentoList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterarTipoDoc(array $tipoDoc): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"TipoDocumento\" SET DescTipoDoc = ? WHERE IdTipoDoc = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($tipoDoc as $td) {
                $tipoDocData = new TipoDocumento(
                    $td['id'],
                    $td['desctipo'],
                    $td['armario']
                );
            }

            $stmt->bindValue(1, $tipoDocData->descTipo());
            $stmt->bindValue(2, $tipoDocData->armario());
            $stmt->bindValue(3, $tipoDocData->id());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluirTipoDocumento(int $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"TipoDocumento\" where IdTipoDoc  = ?;";
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
