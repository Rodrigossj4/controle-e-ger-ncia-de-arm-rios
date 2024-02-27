<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\PerfilAcessoServices;

class PerfilAcessoController extends Controller
{    
    public function __construct()
    {
        
    }

    public function index()
    {
        $this->validarSessao();
        $service = new PerfilAcessoServices();
       
        $PerfilAcessoList = $service->listaPerfis();  
        
        require __DIR__ . '../../Views/Perfis/index.php';
    }

    public function cadastrar():bool
     {       
        $perfilList = array();
        array_push($perfilList, array(
            'nomeperfil' => filter_input(INPUT_POST, 'nomePerfil')
        ));

        $service = new PerfilAcessoServices();
        
        return $service->cadastrarPerfis($perfilList);      
     }

     public function listar()
     {
        header('Content-Type: application/json; charset=utf-8');     
        $service = new PerfilAcessoServices();
        echo json_encode($service->listaPerfis()); 
     }

     public function alterar():bool
     {  
      
        $perfilList = array();
        array_push($perfilList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'nomeperfil' => filter_input(INPUT_POST, 'nomeperfil')
        ));
       
        $service = new PerfilAcessoServices();
        $service->alterar($perfilList);
        return true;
     }

     public function excluir():bool
     {        
        $service = new PerfilAcessoServices();
        $service->excluir(filter_input(INPUT_POST, 'id'));
        return true;
     }
}