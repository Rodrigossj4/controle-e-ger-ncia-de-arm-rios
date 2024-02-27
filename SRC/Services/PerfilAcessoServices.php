<?php

namespace Marinha\Mvc\Services;
use Exception;

use Marinha\Mvc\Infra\Repository\PerfilAcessoRepository;


class PerfilAcessoServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function cadastrarPerfis(array $perfil): bool
    {       
        try{
            $repository = new PerfilAcessoRepository($this->Conexao());       
            return $repository->cadastrarPerfis($perfil);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function listaPerfis(): array
    {
        try{
            $repository = new PerfilAcessoRepository($this->Conexao());       
            return $repository->listaPerfis();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function alterar(array $perfil): bool
    {       
        try{            
            $repository = new  PerfilAcessoRepository($this->Conexao());       
            return $repository->alterar($perfil);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    public function excluir(int $id): bool
    {      
        try{
            $repository = new PerfilAcessoRepository($this->Conexao());       
            return $repository->excluir($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
}