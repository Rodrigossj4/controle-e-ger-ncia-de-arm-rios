<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Models\TipoDocumento;
use Marinha\Mvc\Services\TipoDocumentoService;

class TipoDocumentoController 
{
    
    public function __construct()
    {
        
    }

    public function index()
    {
        $service = new TipoDocumentoService();
        $TipoDocumentoList = $service->listaTipoDocumento();  
        require __DIR__ . '../../Views/tipoDocumento/index.php';
    }

    public function cadastrarTipodocumento():bool
    {
       $tipoDocList = array();
       array_push($tipoDocList, array(
           'desctipo' => filter_input(INPUT_POST, 'desctipo')
       ));

       $service = new TipoDocumentoService();
       
       return $service->cadastrarTipoDocumento($tipoDocList);      
    }

    public function listar()
     {
        header('Content-Type: application/json; charset=utf-8');     
        $service = new TipoDocumentoService();
        echo json_encode($service->listaTipoDocumento()); 
     }

     public function listarTipoDocumentoArmarios()
     {
        $idArmario = filter_input(INPUT_GET, 'id');
        header('Content-Type: application/json; charset=utf-8');     
        $service = new TipoDocumentoService();
        echo json_encode($service->listaTipoDocumentoArmario($idArmario)); 
     }

    public function alterar():bool
     {  
        $tipoDocList = array();
        array_push($tipoDocList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'desctipo' => filter_input(INPUT_POST, 'descTipoDoc')
        ));
       
        $service = new TipoDocumentoService();
        $service->alterarTipoDoc($tipoDocList);
        return true;
     }

    public function excluir():bool
     {      
        $service = new TipoDocumentoService();
        $service->excluirTipoDocumento(filter_input(INPUT_POST, 'id'));
        return true;
     }
}