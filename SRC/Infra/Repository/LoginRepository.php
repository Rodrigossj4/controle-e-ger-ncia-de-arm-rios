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
            $sqlQuery = "select us.\"IdUsuario\", us.\"NomeUsuario\", us.\"Nip\", us.\"PerfilUsuario\", us.\"OMUsuario\" , us.\"SetorUsuario\", per.\"nivelAcesso\" from {$this->schema}\"Usuarios\" us inner join  {$this->schema}\"PerfilUsuario\" per on us.\"PerfilUsuario\" = per.\"IdPerfilUsuario\" where \"Nip\" = ? and \"SenhaUsuario\" = ?";
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
                    'nivelAcesso' => $usuarioData['nivelAcesso'],
                    'omusuario' => $usuarioData['OMUsuario'],
                    'setorusuario' => $usuarioData['SetorUsuario'],
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
