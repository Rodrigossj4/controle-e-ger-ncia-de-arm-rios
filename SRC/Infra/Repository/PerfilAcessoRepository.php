<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\PerfilAcesso;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class PerfilAcessoRepository extends LogRepository
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrarPerfis(array $perfil): int
    {
        try {

            $sqlQuery = "INSERT INTO {$this->schema}\"PerfilUsuario\"(\"DescPerfil\", \"nivelAcesso\") values(?, ?) RETURNING \"IdPerfilUsuario\";";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($perfil as $pr) {
                $perfilData = new PerfilAcesso(
                    null,
                    $pr['nomeperfil'],
                    $pr['nivelAcesso']
                );
            }

            $stmt->bindValue(1, $perfilData->nomePerfil());
            $stmt->bindValue(2, $perfilData->nivelAcesso());
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function listaPerfis(): array
    {
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"PerfilUsuario\" order by \"DescPerfil\" desc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $perfilDataList = $stmt->fetchAll();
            $perfilList = array();
            foreach ($perfilDataList as $perfilData) {
                array_push($perfilList, array(
                    'id' => $perfilData['IdPerfilUsuario'],
                    'nomeperfil' => $perfilData['DescPerfil']
                ));
            };

            return $perfilList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function exibirDadosPerfil(string $id): array
    {
        try {
            $sqlQuery = "SELECT \"DescPerfil\", \"nivelAcesso\" FROM {$this->schema}\"PerfilUsuario\" WHERE \"IdPerfilUsuario\" = ? Limit 1 FOR UPDATE;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $perfilDataList = $stmt->fetchAll();
            $perfilList = array();
            foreach ($perfilDataList as $perfilData) {
                array_push($perfilList, array(
                    'descperfil' => $perfilData['DescPerfil'],
                    'nivelacesso' => $perfilData['nivelAcesso'],
                    'armarios' => $this->listarArmariosPerfil($id)
                ));
            };

            //var_dump($perfilList);
            return $perfilList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function alterar(array $perfil): bool
    {
        try {

            $sqlQuery = "UPDATE {$this->schema}\"PerfilUsuario\" SET \"DescPerfil\" = ? , \"nivelAcesso\" = ? WHERE \"IdPerfilUsuario\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($perfil as $td) {
                $perfilData = new PerfilAcesso(
                    $td['id'],
                    $td['nomeperfil'],
                    $td['nivelAcesso']
                );
            }

            $stmt->bindValue(1, $perfilData->nomePerfil());
            $stmt->bindValue(2, $perfilData->nivelAcesso());
            $stmt->bindValue(3, $perfilData->id());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function excluir(int $id): bool
    {
        try {
            $sqlQuery = "delete FROM {$this->schema}\"PerfilUsuario\" where \"IdPerfilUsuario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function BuscarPerfil(array $perfilList): int
    {
        $perfil = $perfilList['0']["nomeperfil"];
        try {
            $sqlQuery = "SELECT * FROM {$this->schema}\"PerfilUsuario\" WHERE LOWER(\"DescPerfil\") = LOWER('$perfil');";
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

    public function vincularPerfisArmario(array $perfil, int $idPerfil)
    {
        foreach ($perfil["0"]["armarios"] as $pr) {
            $sqlQuery = "INSERT INTO {$this->schema}\"PerfilUsuarioArmarios\"(\"IdPerfilUsuario\", \"IdArmario\") values(?, ?);";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idPerfil);
            $stmt->bindValue(2, $pr);
            $stmt->execute();
        }
    }

    public function removerVinculosPerfisArmario(int $idPerfil)
    {
        $sqlQuery = "DELETE FROM {$this->schema}\"PerfilUsuarioArmarios\" WHERE \"IdPerfilUsuario\" =  ?;";
        $stmt = $this->pdo->prepare($sqlQuery);
        $stmt->bindValue(1, $idPerfil);
        $stmt->execute();
    }

    public function listarArmariosPerfil(int $idPerfil): array
    {
        try {
            $sqlQuery = "SELECT \"IdArmario\" FROM {$this->schema}\"PerfilUsuarioArmarios\" WHERE \"IdPerfilUsuario\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idPerfil);
            $stmt->execute();

            $perfilDataList = $stmt->fetchAll();
            $perfilList = array();
            foreach ($perfilDataList as $perfilData) {
                array_push($perfilList, array(
                    $perfilData['IdArmario']
                ));
            };

            return $perfilList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
