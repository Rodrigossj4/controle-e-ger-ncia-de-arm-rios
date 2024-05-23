<?php

namespace Marinha\Mvc\Infra\Repository;

use Marinha\Mvc\Models\Armarios;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class LoginRepository extends LogRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function login(array $usuario): array
    {
        try {
            $sqlQuery = "select \"IdUsuario\", \"NomeUsuario\", \"Nip\", \"PerfilUsuario\" from {$this->schema}\"Usuarios\" where \"Nip\" = ? and \"SenhaUsuario\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $usuario[0]['nip']);
            $stmt->bindValue(2, hash('sha256', $usuario[0]['nip'] . $usuario[0]['senhausuario']));
            $stmt->execute();

            $usuarioDataList = $stmt->fetchAll();
            $usuarioList = array();
            foreach ($usuarioDataList as $usuarioData) {
                array_push($usuarioList, array(
                    'codusuario' => $usuarioData['IdUsuario'],
                    'nomeusuario' => $usuarioData['NomeUsuario'],
                    'nip' => $usuarioData['Nip'],
                    'idacesso' => $usuarioData['PerfilUsuario'],
                    'dataLogin' => Date("d/m/Y h:i:s")
                ));
            };

            return $usuarioList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
