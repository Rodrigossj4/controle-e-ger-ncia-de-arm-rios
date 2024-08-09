<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Usuarios;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class UsuarioRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listaUsuarios(): array
    {
        try {
            $sqlQuery = "SELECT * FROM  {$this->schema}\"Usuarios\" order by \"NomeUsuario\" asc;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();

            $usuariosDataList = $stmt->fetchAll();
            $usuariosList = array();
            foreach ($usuariosDataList as $usuariosData) {
                array_push($usuariosList, array(
                    'codusuario' => $usuariosData['IdUsuario'],
                    'nomeusuario' => $usuariosData['NomeUsuario'],
                    'nip' => $usuariosData['Nip'],
                    'senhausuario' => $usuariosData['SenhaUsuario'],
                    'idacesso' => $usuariosData['PerfilUsuario']
                ));
            };

            return $usuariosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrarUsuario(array $usuario)
    {
        try {
            // var_dump($usuario);
            $sqlQuery = "INSERT INTO {$this->schema}\"Usuarios\"(\"NomeUsuario\",\"Nip\", \"SenhaUsuario\", \"PerfilUsuario\", \"OMUsuario\" , \"SetorUsuario\") values(?, ?, ?, ?,?,?);";
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($usuario as $us) {
                $usuarioData = new Usuarios(
                    null,
                    $us['nomeusuario'],
                    $us['nip'],
                    $us['senhausuario'],
                    $us['idacesso'],
                    $us['om'],
                    $us['setor']
                );
            }

            $stmt->bindValue(1, $usuarioData->NomeUsuario());
            $stmt->bindValue(2, $usuarioData->Nip());
            $stmt->bindValue(3, $usuarioData->SenhaUsuario());
            $stmt->bindValue(4, $usuarioData->idAcesso());
            $stmt->bindValue(5, $usuarioData->OM());
            $stmt->bindValue(6, $usuarioData->setor());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function alterarUsuario(array $usuario): bool
    {
        try {

            //$sqlQuery = 'UPDATE usuarios SET nomeusuario = ?, nip = ?, senhausuario = ?, idacesso = ? WHERE codusuario = ?';
            $sqlQuery = "UPDATE {$this->schema}\"Usuarios\" SET \"NomeUsuario\" = ?, \"Nip\" = ? , \"PerfilUsuario\" = ? , \"OMUsuario\" = ? , \"SetorUsuario\" = ? WHERE \"IdUsuario\" = ?";

            $stmt = $this->pdo->prepare($sqlQuery);

            foreach ($usuario as $us) {
                $usuarioData = new Usuarios(
                    $us['codusuario'],
                    $us['nomeusuario'],
                    $us['nipusuario'],
                    "",
                    $us['idacesso'],
                    $us['omusuario'],
                    $us['setorusuario']
                );
            }

            $stmt->bindValue(1, $usuarioData->NomeUsuario());
            $stmt->bindValue(2, $usuarioData->Nip());
            $stmt->bindValue(3, $usuarioData->idAcesso());
            $stmt->bindValue(4, $usuarioData->OM());
            $stmt->bindValue(5, $usuarioData->setor());
            $stmt->bindValue(6, $usuarioData->codUsuario());
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function excluirUsuario(int $id): bool
    {
        try {
            $sqlQuery = "update {$this->schema}\"Usuarios\" set \"Ativo\" = False  where \"IdUsuario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function retornaNipUsuario(int $id): string
    {
        try {
            $sqlQuery = "Select \"Nip\" FROM {$this->schema}\"Usuarios\" where \"IdUsuario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $nip =  $stmt->fetchAll();
            return $nip[0]['Nip'];
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }
    public function totalUsuariosPerfil(int $idPerfil): int
    {
        try {
            $sqlQuery = "SELECT count(\"IdUsuario\") as \"totalUsuario\" FROM  {$this->schema}\"Usuarios\" WHERE \"PerfilUsuario\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idPerfil);
            $stmt->execute();

            $usuariosDataList = $stmt->fetchAll();

            // var_dump($usuariosDataList['0']['totalUsuario']);

            return $usuariosDataList['0']['totalUsuario'];
        } catch (Exception $e) {
            echo $e;
            return 1;
        }
    }

    public function BuscarUsuarioNip($nip): int
    {
        try {
            $sqlQuery = "SELECT count(\"IdUsuario\") as \"usuario\" FROM  {$this->schema}\"Usuarios\" WHERE \"Nip\" = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $nip);
            $stmt->execute();

            $usuariosDataList = $stmt->fetchAll();

            // var_dump($usuariosDataList['0']['totalUsuario']);

            return $usuariosDataList['0']['usuario'];
        } catch (Exception $e) {
            echo $e;
            return 1;
        }
    }

    public function validarSenhaUsuario(array $usuario): ?int
    {
        try {
            $sqlQuery = "select \"IdUsuario\" from {$this->schema}\"Usuarios\" where \"Nip\" = ? and \"SenhaUsuario\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $usuario[0]['nip']);
            $stmt->bindValue(2, hash('sha256', $usuario[0]['nip'] . $usuario[0]['senha']));
            $stmt->execute();
            $usuarioDataList = $stmt->fetchAll();
            var_dump($sqlQuery);
            return  $usuarioDataList[0]['IdUsuario'];
        } catch (Exception $e) {
            echo $e;
            return null;
        }
    }

    public function AlterarSenhaUsuario($dadosUsuarios): bool
    {

        try {
            $sqlQuery = "update {$this->schema}\"Usuarios\" set \"SenhaUsuario\" = ?, \"DataUltimoLogin\" = ?  where \"IdUsuario\"  = ?;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, hash('sha256', $dadosUsuarios[0]['nip'] . $dadosUsuarios[0]['novaSenha']));
            $stmt->bindValue(2, date('Y-m-d H:i:s'));
            $stmt->bindValue(3, $dadosUsuarios[0]["idUsuario"]);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function buscarUsuarioPorID(string $id): array
    {
        try {
            $sqlQuery = "SELECT \"NomeUsuario\",\"Nip\", \"PerfilUsuario\", \"OMUsuario\" , \"SetorUsuario\" FROM  {$this->schema}\"Usuarios\"  where \"IdUsuario\"  = ? Limit 1 FOR UPDATE;";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();

            $usuariosDataList = $stmt->fetchAll();
            $usuariosList = array();
            foreach ($usuariosDataList as $usuariosData) {
                array_push($usuariosList, array(
                    'nomeusuario' => $usuariosData['NomeUsuario'],
                    'nip' => $usuariosData['Nip'],
                    'idacesso' => $usuariosData['PerfilUsuario'],
                    'omusuario' => $usuariosData['OMUsuario'],
                    'setorusuario' => $usuariosData['SetorUsuario'],
                ));
            };

            return $usuariosList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
