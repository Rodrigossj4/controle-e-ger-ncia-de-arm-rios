<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\UsuarioRepository;

class UsuarioServices
{
    public function __construct()
    {
    }

    public function listaUsuarios(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new UsuarioRepository($pdo);       
            return $repository->listaUsuarios();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    public function cadastrarUsuario(array $usuario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new UsuarioRepository($pdo);       
            return $repository->cadastrarUsuario($usuario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function alterarUsuario(array $armario): bool
    {       
        try{
            var_dump($armario);
            $pdo = Conexao::createConnection();        
            $repository = new UsuarioRepository($pdo);       
            return $repository->alterarUsuario($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    public function excluirUsuario(int $id): bool
    {        
        try{
            $pdo = Conexao::createConnection();        
            $repository = new UsuarioRepository($pdo);       
            return $repository->excluirUsuario($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
}