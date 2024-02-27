<?php

namespace Marinha\Mvc\Services;

use Marinha\Mvc\Infra\Repository\Conexao;
use Exception;
use Marinha\Mvc\Infra\Repository\SistemaRepository;
class SistemaServices 
{
    public function Conexao(){
        return Conexao::createConnection();
    }

    public function gravarLogOperacoes(array $log)
    {
        try{                   
            $repository = new SistemaRepository($this->Conexao());       
            return $repository->gravarLogOperacoes($log);

        }catch(Exception $e){
            echo $e;           
        } 
    }

}