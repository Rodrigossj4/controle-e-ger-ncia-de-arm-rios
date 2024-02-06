<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\DocumentoServices;

class DocumentoController
{
    public function __construct()
    {

    }

    public function index()
    {
        $service = new DocumentoServices();
        $DocumentosList = $service->listaDocumentos();  
        require __DIR__ . '../../Views/documento/index.php';
    }

    public function cadastrarDocumento():bool
     {
        $documentosList = array();
        array_push($documentosList, array(
            'docid' => filter_input(INPUT_POST, 'DocId'),
            'armario' => filter_input(INPUT_POST, 'Armario'),
            'tipodoc' => filter_input(INPUT_POST, 'TipoDoc'),
            'folderid' => filter_input(INPUT_POST, 'FolderId'),
            'semestre' => filter_input(INPUT_POST, 'semestre'),
            'ano' => filter_input(INPUT_POST, 'ano'),
            'nip' => filter_input(INPUT_POST, 'Nip')
        ));

        $service = new DocumentoServices();
        
        return $service->cadastrarDocumentos($documentosList);      
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

     public function cadastrarPagina():bool
     {
        $paginasList = array();
        array_push($paginasList, array(            
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