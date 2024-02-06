<?php

namespace Marinha\Mvc\Infra\Repository;
use Marinha\Mvc\Models\Armarios;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;
class ArmarioRepository {
    private $pdo;
   
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        
    }
    public function listaArmarios(): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM  armarios;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();
       
            $armariosDataList = $stmt->fetchAll();
            $armariosList = array();
            foreach ($armariosDataList as $armariosData) {                   
                array_push($armariosList, array(
                    'id' => $armariosData['id'],
                    'codigo' => $armariosData['codigo'],
                    'nomeinterno' => $armariosData['nomeinterno'],
                    'nomeexterno' => $armariosData['nomeexterno']
                ));
            };

            return $armariosList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }

    public function cadastrarArmarios(array $armario): bool
    {       
        try{
  
            $sqlQuery = 'INSERT INTO armarios(codigo, nomeinterno, nomeexterno) values(?, ?, ?);';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($armario as $ar){
                $armarioData = new Armarios(
                    null,
                    $ar['codigo'],
                    $ar['nomeinterno'],
                    $ar['nomeexterno'],
                );
            }
                   
            $stmt->bindValue(1, $armarioData->codigo());
            $stmt->bindValue(2, $armarioData->nomeInterno());
            $stmt->bindValue(3, $armarioData->nomeExterno());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function alterarArmarios(array $armario): bool
    {       
        try{
  
            $sqlQuery = 'UPDATE armarios SET codigo = ?, nomeinterno = ?, nomeexterno = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($armario as $ar){
                $armarioData = new Armarios(
                    $ar['id'],
                    $ar['codigo'],
                    $ar['nomeinterno'],
                    $ar['nomeexterno'],
                );
            }
                   
            $stmt->bindValue(1, $armarioData->codigo());
            $stmt->bindValue(2, $armarioData->nomeInterno());
            $stmt->bindValue(3, $armarioData->nomeExterno());
            $stmt->bindValue(4, $armarioData->id());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }
    public function excluirArmario(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM armarios where id  = ?;';
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