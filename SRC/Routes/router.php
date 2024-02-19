<?php
namespace Marinha\Mvc\routes;
use Exception;
use Marinha\Mvc\Helpers\Request;
use Marinha\Mvc\Helpers\Uri;
use Throwable;

Class Router
{    
    const CONTROLLER_NAMESPACE = 'Marinha\\Mvc\\Controllers';
    public static function load(string $controller, string $metodo)
    {        
        try{

            $controllerNameSpace = self::CONTROLLER_NAMESPACE.'\\'.$controller;
            if(!class_exists($controllerNameSpace)){
                throw new Exception("O Controller {$controller} não existe");
            }

            $controllerInstance = new $controllerNameSpace;

            if(!method_exists($controllerInstance, $metodo)){
                throw new Exception("O método {$metodo} não existe no controller {$controller}");
            }

            $controllerInstance->$metodo();
            
        }catch(Throwable $t){
            echo $t->getMessage();
        }
    }

    public static function routes():array
    {
        
        return [
            'GET'=> [
                '/' => fn () => self::load('ArmariosController','index'),
                '/listarArmarios' => fn () => self::load('ArmariosController','listar'),
                '/listarTipodocumento' => fn () => self::load('TipoDocumentoController','listar'),
                '/gerenciar-tipo-documentos' => fn () => self::load('TipoDocumentoController','index'),
                '/gerenciar-documentos' => fn () => self::load('DocumentoController','index'),
                '/listarPaginas' => fn () => self::load('DocumentoController','listarPaginas'),
                '/listarTipoDocumentosArmario' => fn () => self::load('TipoDocumentoController','listarTipoDocumentoArmarios') ,
                '/retornarCaminhoDocumento' => fn () => self::load('DocumentoController','retornarCaminhoDocumento'),
                '/visualizarDocumento' => fn () => self::load('DocumentoController','visualizarDocumento'),
                '/assinarDocumento' => fn () => self::load('DocumentoController','asssinarDigital')                       
            ],
            'POST'=> [
                '/excluirArmario' => fn () => self::load('ArmariosController', 'excluir'),
                '/cadastrarArmario' => fn () => self::load('ArmariosController', 'cadastrar'),
                '/alterarArmario' => fn () => self::load('ArmariosController', 'alterar'),
                '/cadastrarTipoDocumento' => fn () => self::load('TipoDocumentoController', 'cadastrarTipoDocumento'),
                '/excluirTipoDocumento' => fn () => self::load('TipoDocumentoController', 'excluir'),
                '/alterarTipoDoc' => fn () => self::load('TipoDocumentoController', 'alterar'),
                '/cadastrarDocumento' => fn () => self::load('DocumentoController', 'cadastrarDocumento'),
                '/excluirDocumento' => fn () => self::load('DocumentoController', 'excluir'),
                '/alterarDocumento' => fn () => self::load('DocumentoController', 'alterarDocumento'),
                '/cadastrarPagina' => fn () => self::load('DocumentoController', 'cadastrarPagina'),
                '/excluirPagina' => fn () => self::load('DocumentoController','excluirPagina'),
                '/alterarPagina' => fn () => self::load('DocumentoController','alterarPagina')   ,
                '/retornarCaminhoDocumento' => fn () => self::load('DocumentoController','retornarCaminhoDocumento')    
            ]
        ];
    }

    public static function execute()
    {
        
        try{
            $routes = self::routes();
            $request = Request::get();
            $Uri = Uri::get('path');

           //var_dump(isset($routes[$request]));
            if(!isset($routes[$request]))
            {
                throw new Exception('A rota não existe 1');
            }
           
            //var_dump($Uri);
            //var_dump($routes[$request]);
            if(!array_key_exists($Uri, $routes[$request]))
            {
                throw new Exception('A rota não existe 2');
            }

            $router = $routes[$request][$Uri];

            if(!is_callable($router)){
                throw new Exception("A rota {$router} não foi implementada");
            }

            $router();
        }catch(Throwable $t){
            echo $t->getMessage();
        }
    }
}