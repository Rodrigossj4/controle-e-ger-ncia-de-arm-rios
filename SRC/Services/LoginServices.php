<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\LoginRepository;

class LoginServices extends SistemaServices
{
    public function __construct()
    {
    }

    public function login(array $usuario): bool
    {       
        try{                 
            $repository = new LoginRepository($this->Conexao());
            $retorno = $repository->login($usuario);  
           
            if(count($retorno) == 1){
                session_start();
                session_regenerate_id(true);
                $_SESSION['usuario'] = $retorno;               
                return true;
            }else{
                return false;
            }           

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function logout(): bool
    {       
        try{
            session_start();
            $_SESSION['usuario'] = null;
            session_destroy(); 
            return true;         

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
}