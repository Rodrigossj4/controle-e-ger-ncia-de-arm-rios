<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;
use Marinha\Mvc\Infra\Repository\TipoDocumentoRepository;

class TipoDocumentoService
{
    public function __construct()
    {
    }

    public function cadastrarTipoDocumento(array $armario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new TipoDocumentoRepository($pdo);       
            return $repository->cadastrarTipoDocumento($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function listaTipoDocumento(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new TipoDocumentoRepository($pdo);       
            return $repository->listaTipoDocumento();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    public function listaTipoDocumentoArmario(int $idArmario): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new TipoDocumentoRepository($pdo);       
            return $repository->listaTipoDocumentoArmarios($idArmario);

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function alterarTipoDoc(array $tipoDoc): bool
    {       
        try{
           
            $pdo = Conexao::createConnection();        
            $repository = new TipoDocumentoRepository($pdo);       
            return $repository->alterarTipoDoc($tipoDoc);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    
    public function excluirTipoDocumento(int $id): bool
    {
        var_dump($id);
        try{
            $pdo = Conexao::createConnection();        
            $repository = new TipoDocumentoRepository($pdo);       
            return $repository->excluirTipoDocumento($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
    
}