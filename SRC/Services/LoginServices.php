<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\LoginRepository;


class LoginServices
{
    public function __construct()
    {
    }

    public function login(array $usuario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new LoginRepository($pdo);
            $retorno = $repository->login($usuario);  
           
            if(count($retorno) == 1){
                session_start();
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
}