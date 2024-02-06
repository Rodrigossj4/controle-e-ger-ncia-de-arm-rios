<?php

namespace Marinha\Mvc\Services;
use Exception;
use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\DocumentoRepository;

class DocumentoServices
{
    public function __construct()
    {
    }
    public function listaDocumentos(): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->listaDocumentos();

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }
    
    public function cadastrarDocumentos(array $armario): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->cadastrarDocumentos($armario);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function alterarDocmentos(array $documento): bool
    {       
        try{
            var_dump($documento);
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->alterarDocumentos($documento);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
    
    public function excluirDocumentos(int $id): bool
    {
        var_dump($id);
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->excluirDocumentos($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }


    public function listaPaginas(int $id): array
    {
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->listarPaginas($id);

        }catch(Exception $e){
            echo $e;
            return [];
        }  
    }

    public function cadastrarPaginas(array $pagina): bool
    {       
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->cadastrarPagina($pagina);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }

    public function excluirPagina(int $id): bool
    {
        var_dump($id);
        try{
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->excluirPagina($id);

        }catch(Exception $e){
            echo $e;
            return false;
        } 
    }
    
    public function alterarPaginas(array $pagina): bool
    {       
        try{
            var_dump($pagina);
            $pdo = Conexao::createConnection();        
            $repository = new DocumentoRepository($pdo);       
            return $repository->alterarPagina($pagina);

        }catch(Exception $e){
            echo $e;
            return false;
        }  
    }
}