<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\PerfilAcessoRepository;


class PerfilAcessoServices
{
    public function __construct()
    {
    }

    public function cadastrarPerfis(array $perfil): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new PerfilAcessoRepository($pdo);       
            return $repository->cadastrarPerfis($perfil);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function listaPerfis(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new PerfilAcessoRepository($pdo);       
            return $repository->listaPerfis();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function alterar(array $perfil): bool
    {       
        var_dump($perfil);
        try{            
            $pdo = Conexao::createConnection();        
            $repository = new  PerfilAcessoRepository($pdo);       
            return $repository->alterar($perfil);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    public function excluir(int $id): bool
    {      
        try{
            $pdo = Conexao::createConnection();        
            $repository = new PerfilAcessoRepository($pdo);       
            return $repository->excluir($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
}