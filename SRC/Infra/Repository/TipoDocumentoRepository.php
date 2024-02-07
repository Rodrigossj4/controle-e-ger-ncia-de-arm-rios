<?php

namespace Marinha\Mvc\Infra\Repository;
use Marinha\Mvc\Models\TipoDocumento;
use Marinha\Mvc\Infra\Repository\interfaces;
use Exception;
#implements IArmarioRepository
use PDO;

class TipoDocumentoRepository{
    private $pdo;
   
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        
    }

    public function cadastrarTipoDocumento(array $tipoDocumento): bool
    {       
        try{
  
            $sqlQuery = 'INSERT INTO tipodocumento(desctipo, armario) values(? , ?);';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($tipoDocumento as $dc){
                $tipoDocumentoData = new TipoDocumento(
                    null,
                    $dc['desctipo'],
                    $dc['armario']
                );
            }
                   
            $stmt->bindValue(1, $tipoDocumentoData->descTipo());
            $stmt->bindValue(2, $tipoDocumentoData->armario());
            $stmt->execute();
       
            return true;
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }

    public function listaTipoDocumento(): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM  tipodocumento;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute();
       
            $tipodocumentoDataList = $stmt->fetchAll();
            $tipodocumentoList = array();
            foreach ($tipodocumentoDataList as $tipodocumentoData) {                   
                array_push($tipodocumentoList, array(
                    'id' => $tipodocumentoData['id'],
                    'desctipo' => $tipodocumentoData['desctipo'],
                    'armario' => $tipodocumentoData['armario']
                ));
            };
           
            return $tipodocumentoList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }

    public function listaTipoDocumentoArmarios(int $idArmario): array
    {       
        try{
            $sqlQuery = 'SELECT * FROM  tipodocumento WHERE armario = ?;';
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->bindValue(1, $idArmario);
            $stmt->execute();  
       
            $tipodocumentoDataList = $stmt->fetchAll();
            $tipodocumentoList = array();
            foreach ($tipodocumentoDataList as $tipodocumentoData) {                   
                array_push($tipodocumentoList, array(
                    'id' => $tipodocumentoData['id'],
                    'desctipo' => $tipodocumentoData['desctipo']
                ));
            };
           
            return $tipodocumentoList;
        }catch (Exception $e){
                echo $e;
                return [];
        }   
    }
    
    public function alterarTipoDoc(array $tipoDoc): bool
    {       
        try{
 
            $sqlQuery = 'UPDATE tipodocumento SET desctipo = ?, armario = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sqlQuery);

            foreach($tipoDoc as $td){
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
        }catch (Exception $e){
                echo $e;
                return false;
        }   
    }
    
    public function excluirTipoDocumento(int $id): bool
    {
        try{
            $sqlQuery = 'delete FROM tipodocumento where id  = ?;';
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