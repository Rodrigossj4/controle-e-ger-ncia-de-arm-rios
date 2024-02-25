<?php

namespace Marinha\Mvc\Infra\Repository;
use Marinha\Mvc\Models\Usuarios;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;
class UsuarioRepository {
    private $pdo;
   
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function listaUsuarios(): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM  usuarios;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();
       
            $usuariosDataList = $stmt->fetchAll();
            $usuariosList = array();
            foreach ($usuariosDataList as $usuariosData) {                   
                array_push($usuariosList, array(
                    'codusuario' => $usuariosData['codusuario'],
                    'nomeusuario' => $usuariosData['nomeusuario'],
                    'nip' => $usuariosData['nip'],
                    'senhausuario' => $usuariosData['senhausuario'],
                    'idacesso' => $usuariosData['idacesso']
                ));
            };

            return $usuariosList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }

    public function cadastrarUsuario(array $usuario): bool
    {       
        try{
            var_dump($usuario);
            $sqlQuery = 'INSERT INTO usuarios(nomeusuario, nip, senhausuario, idacesso) values(?, ?, ?, ?);';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($usuario as $us){
                $usuarioData = new Usuarios(
                    null,
                    $us['nomeusuario'],
                    $us['nip'],
                    $us['senhausuario'],
                    $us['idacesso']
                );
            }
                   
            
            $stmt->bindValue(1, $usuarioData->NomeUsuario());
            $stmt->bindValue(2, $usuarioData->Nip());
            $stmt->bindValue(3, $usuarioData->SenhaUsuario());
            $stmt->bindValue(4, $usuarioData->idAcesso());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function alterarUsuario(array $usuario): bool
    {       
        try{
  
            //$sqlQuery = 'UPDATE usuarios SET nomeusuario = ?, nip = ?, senhausuario = ?, idacesso = ? WHERE codusuario = ?';
            $sqlQuery = 'UPDATE usuarios SET nomeusuario = ? WHERE codusuario = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($usuario as $us){
                $usuarioData = new Usuarios(
                    $us['codusuario'],
                    $us['nomeusuario'],
                    "",
                    "",
                    1
                );
            }
                   
            $stmt->bindValue(1, $usuarioData->NomeUsuario());
           /* $stmt->bindValue(2, $usuarioData->Nip());
            $stmt->bindValue(3, $usuarioData->SenhaUsuario());
            $stmt->bindValue(4, $usuarioData->idAcesso());*/
            $stmt->bindValue(2, $usuarioData->codUsuario());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }
    public function excluirUsuario(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM usuarios where codusuario  = ?;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $id);
            $stmt->execute();                   

            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }  
    }
}