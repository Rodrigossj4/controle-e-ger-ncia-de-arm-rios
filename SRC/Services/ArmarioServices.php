<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\ArmarioRepository;


class ArmarioServices
{
    public function __construct()
    {
    }
    public function listaArmarios(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new ArmarioRepository($pdo);       
            return $repository->listaArmarios();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function cadastrarArmarios(array $armario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new ArmarioRepository($pdo);       
            return $repository->cadastrarArmarios($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function alterarArmarios(array $armario): bool
    {       
        try{
            var_dump($armario);
            $pdo = Conexao::createConnection();        
            $repository = new ArmarioRepository($pdo);       
            return $repository->alterarArmarios($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    public function excluirArmario(int $id): bool
    {
        var_dump($id);
        try{
            $pdo = Conexao::createConnection();        
            $repository = new ArmarioRepository($pdo);       
            return $repository->excluirArmario($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
}