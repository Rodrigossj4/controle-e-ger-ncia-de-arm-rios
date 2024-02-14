<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\DocumentoServices;

class DocumentoController
{
    public function __construct()
    {

    }

    public function index()
    {
        $service = new DocumentoServices();
        $armariosService =  new ArmarioServices();

        $DocumentosList = $service->listaDocumentos(); 
        $ArmariosList = $armariosService->listaArmarios();
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function cadastrarDocumento():bool
     {
        $idPasta = random_int(1,999999);
        $service = new DocumentoServices();

        $service->gerarPastaDoc($idPasta);
        
        $documentosList = array();
        array_push($documentosList, array(
            'docid' => $idPasta,
            'armario' => filter_input(INPUT_POST, 'ListArmarioDocumento'),
            'tipodoc' => filter_input(INPUT_POST, 'SelectTipoDoc'),
            'folderid' => "1",
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano'),
            'nip' => filter_input(INPUT_POST, 'Nip')
        ));

        
        $tags = filter_input(INPUT_POST, 'Nip'). ", ".  filter_input(INPUT_POST, 'ano');
        //var_dump($documentosList);
        if($service->cadastrarDocumentos($documentosList))
        {
            $this->cadastrarPagina($idPasta, 1, $service->gerarArquivo($idPasta, $tags));
        }
        return true;      
     }

     public function alterarDocumento():bool
     {  
        $documentosList = array();
        array_push($documentosList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'docid' => filter_input(INPUT_POST, 'docId'),
            'nip' => filter_input(INPUT_POST, 'nip'),
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano'),
            'tipodocumento' => filter_input(INPUT_POST, 'tipodocumento'),
            'folderid' => filter_input(INPUT_POST, 'folderid'),
            'armario' => filter_input(INPUT_POST, 'armario')
        ));
               
        $service = new DocumentoServices();
        $service->alterarDocmentos($documentosList);
        return true;
     }
        
     public function excluir():bool
     {        
        $service = new DocumentoServices();
        $service->excluirDocumentos(filter_input(INPUT_POST, 'idDocumento'));
        return true;
     }

     private function cadastrarPagina(int $documentoid, int $numpagina, string $arquivo):bool
     {
        $paginasList = array();
        array_push($paginasList, array(            
            'documentoid' =>  $documentoid,
            'volume' => "1",
            'numpagina' => $numpagina,
            'codexp' => 1,
            'arquivo' => $arquivo,
            'filme' => "1",
            'fotograma' => "1",
            'imgencontrada' => "1"
        ));

        $service = new DocumentoServices();
        
        return $service->cadastrarPaginas($paginasList);      
     }

    public function listarPaginas()
    {
       
        $service = new DocumentoServices();
       
        $paginasList = $service->listaPaginas(filter_input(INPUT_POST, 'iddocumento'));  
        
        require __DIR__ . '../../Views/documento/index.php';
     }

     public function excluirPagina():bool
     {        
        $service = new DocumentoServices();
        $service->excluirPagina(filter_input(INPUT_POST, 'idPagina'));
        return true;
     }

     public function alterarPagina():bool
     {  
        $paginasList = array();
        array_push($paginasList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'documentoid' => filter_input(INPUT_POST, 'documentoid'),
            'volume' => filter_input(INPUT_POST, 'volume'),
            'numpagina' => filter_input(INPUT_POST, 'numpagina'),
            'codexp' => filter_input(INPUT_POST, 'codexp'),
            'arquivo' => filter_input(INPUT_POST, 'arquivo'),
            'filme' => filter_input(INPUT_POST, 'filme'),
            'fotograma' => filter_input(INPUT_POST, 'fotograma'),
            'imgencontrada' => filter_input(INPUT_POST, 'imgencontrada')
        ));
      
        $service = new DocumentoServices();
        $service->alterarPaginas($paginasList);
        return true;
     }

    
}