<?php

namespace Marinha\Mvc\Infra\Repository;
use Marinha\Mvc\Models\PerfilAcesso;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;


class PerfilAcessoRepository {
    private $pdo;
   
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        
    }

    public function cadastrarPerfis(array $perfil): bool
    {       
        try{
  
            $sqlQuery = 'INSERT INTO PerfilAcesso(nomePerfil) values(?);';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($perfil as $pr){
                $perfilData = new PerfilAcesso(
                    null,
                    $pr['nomeperfil'],
                );
            }
                   
            $stmt->bindValue(1, $perfilData->nomePerfil());
           
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function listaPerfis(): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM  PerfilAcesso;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();
       
            $perfilDataList = $stmt->fetchAll();
            $perfilList = array();
            foreach ($perfilDataList as $perfilData) {                   
                array_push($perfilList, array(
                    'id' => $perfilData['id'],
                    'nomeperfil' => $perfilData['nomeperfil']
                ));
            };

            return $perfilList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }

    public function alterar(array $perfil): bool
    {       
        try{
 
            $sqlQuery = 'UPDATE PerfilAcesso SET nomeperfil = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($perfil as $td){
                $perfilData = new PerfilAcesso(
                    $td['id'],
                    $td['nomeperfil']
                );
            }
               
            $stmt->bindValue(1, $perfilData->nomePerfil());
            $stmt->bindValue(2, $perfilData->id());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }
    
    public function excluir(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM PerfilAcesso where id  = ?;';
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