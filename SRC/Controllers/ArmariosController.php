<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\ArmarioServices;


class ArmariosController extends Controller
{
    
    public function __construct()
    {
        
    }

    public function index()
    {   
       $this->validarSessao();
       $service = new ArmarioServices();  
       $ArmariosList = $service->listaArmarios(); 
       require __DIR__ . '../../Views/armarios/index.php';
    }

     public function cadastrar():bool
     {       
        $armariosList = array();
        array_push($armariosList, array(
            'codigo' => filter_input(INPUT_POST, 'codigo'),
            'nomeinterno' => filter_input(INPUT_POST, 'nomeInterno'),
            'nomeexterno' => filter_input(INPUT_POST, 'nomeExterno')
        ));

        $service = new ArmarioServices();
        
        return $service->cadastrarArmarios($armariosList);      
     }
     public function listar()
     {
        header('Content-Type: application/json; charset=utf-8');     
        $service = new ArmarioServices();
        echo json_encode($service->listaArmarios()); 
     }

     public function alterar():bool
     {  
        $armariosList = array();
        array_push($armariosList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'codigo' => filter_input(INPUT_POST, 'codigo'),
            'nomeinterno' => filter_input(INPUT_POST, 'nomeInterno'),
            'nomeexterno' => filter_input(INPUT_POST, 'nomeExterno')
        ));
       
        $service = new ArmarioServices();
        $service->alterarArmarios($armariosList);
        return true;
     }

     public function excluir():bool
     {        
        $service = new ArmarioServices();
        $service->excluirArmario(filter_input(INPUT_POST, 'id'));
        return true;
     }    
}