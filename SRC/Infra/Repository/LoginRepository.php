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
            $sqlQuery = "select us.\"IdUsuario\", us.\"NomeUsuario\", us.\"Nip\", us.\"PerfilUsuario\", us.\"OMUsuario\" , us.\"SetorUsuario\", us.\"DataUltimoLogin\", per.\"nivelAcesso\" from {$this->schema}\"Usuarios\" us inner join  {$this->schema}\"PerfilUsuario\" per on us.\"PerfilUsuario\" = per.\"IdPerfilUsuario\" where \"Nip\" = ? and \"SenhaUsuario\" = ? and \"Ativo\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $usuario[0]['nip']);
            $stmt->bindValue(2, hash('sha256', $usuario[0]['nip'] . $usuario[0]['senhausuario']));
            $stmt->bindValue(3, 1);
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
                    'dataLogin' => Date("d/m/Y h:i:s"),
                    'DataUltimoLogin' => $usuarioData['DataUltimoLogin'] == null ? null : $usuarioData['DataUltimoLogin']
                ));
            };

            if(isset($usuarioDataList)){
                $sqlQuery = "UPDATE {$this->schema}\"Usuarios\" SET \"tentativaLogin\" = ? WHERE \"Nip\" = ?";
                $stmt = $this->pdo->prepare($sqlQuery);
                $stmt->bindValue(1, 0);
                $stmt->bindValue(2, $usuarioData['Nip']);
                $stmt->execute();
            }
            

            return $usuarioList;
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function tentativaLogin($nip)
    {
        try {
            $sqlQuery = "select us.\"tentativaLogin\" from {$this->schema}\"Usuarios\" us where \"Nip\" = ? AND \"Ativo\" = ?";
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $nip);
            $stmt->bindValue(2, 1);
            $stmt->execute();
            $tentativaLoginList = $stmt->fetch();
            if($tentativaLoginList){
                $tentativa = intval($tentativaLoginList['tentativaLogin']) + 1;
                if($tentativa > 3){
                    $sqlQuery = "UPDATE {$this->schema}\"Usuarios\" SET \"Ativo\" = ? WHERE \"Nip\" = ?";
                    $stmt = $this->pdo->prepare($sqlQuery);
                    $stmt->bindValue(1, 0);
                    $stmt->bindValue(2, $nip);
                    $stmt->execute();
                    return $tentativa;
                }else{
                    $sqlQuery = "UPDATE {$this->schema}\"Usuarios\" SET \"tentativaLogin\" = ? WHERE \"Nip\" = ?";
                    $stmt = $this->pdo->prepare($sqlQuery);
                    $stmt->bindValue(1, $tentativa);
                    $stmt->bindValue(2, $nip);
                    $stmt->execute();
                    return $tentativa;
                }
            }else{
                $sqlQuery = "select us.\"tentativaLogin\" from {$this->schema}\"Usuarios\" us where \"Nip\" = ? AND \"Ativo\" = ?  AND \"tentativaLogin\" = ?";
                $stmt = $this->pdo->prepare($sqlQuery);
                $stmt->bindValue(1, $nip);
                $stmt->bindValue(2, 0);
                $stmt->bindValue(3, 3);
                $stmt->execute();
                $tentativaLogin = $stmt->fetch();
                if($tentativaLogin){
                    return 4;
                }else{
                    return 0;
                }
            }
           
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
