<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\LoteServices;

class LotesController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        //$this->validarSessao();
        $service =  new LoteServices();
        $LotesList = $service->listarLotes();        
        require __DIR__ . '../../Views/lotes/index.php';
    }

    public function cadastrar()
    {
        //$this->validarSessao();
        $service =  new LoteServices();
        $lotesList = array();
        array_push($lotesList, array(
           'numeroLote' => filter_input(INPUT_POST, 'numeroLote'),
           'pasta' => $service->gerarPastaLote(random_int(1, 999999))
        ));
        
        return $service->cadastrar($lotesList);       
    }

    public function listar()
    {
        //$this->validarSessao();
        $service =  new LoteServices();
        echo json_encode($service->listarLotes());       
    }
}