<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\OMServices;

class OMController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $this->validarSessao();

        if ($_SESSION['usuario'][0]["nivelAcesso"] != 1)
            header("location: /home");

        $om = new OMServices();
        $OmList = $om->listarOM();
        require __DIR__ . '../../Views/OM/index.php';
    }

    public function cadastrar()
    {
        if (strlen(filter_input(INPUT_POST, 'sigla')) < 1 || strlen(filter_input(INPUT_POST, 'nomeOM')) < 1) {
            http_response_code(500);
            return "Todos os campos são obrigatórios";
        }

        try {
            $armariosList = array();
            array_push($armariosList, array(
                'codOM' => filter_input(INPUT_POST, 'codOM'),
                'sigla' => filter_input(INPUT_POST, 'sigla'),
                'nomeOM' => filter_input(INPUT_POST, 'nomeOM')
            ));

            $service = new OMServices();


            if ($service->cadastrarOM($armariosList)) {
                http_response_code(200);
                return "OM Cadastrado com sucesso";
            }
        } catch (Exception) {
            http_response_code(500);
            return "Houve um problema para cadastrar o armário";
        }
    }

    public function alterar()
    {
        if (strlen(filter_input(INPUT_POST, 'sigla')) < 1 || strlen(filter_input(INPUT_POST, 'nomeOM')) < 1) {
            http_response_code(500);
            return "Todos os campos são obrigatórios";
        }
        var_dump('oi');
        try {
            $armariosList = array();
            array_push($armariosList, array(
                'codOM' => filter_input(INPUT_POST, 'codOM'),
                'sigla' => filter_input(INPUT_POST, 'sigla'),
                'nomeOM' => filter_input(INPUT_POST, 'nomeOM')
            ));

            $service = new OMServices();

            if ($service->atualizarOM($armariosList)) {
                http_response_code(200);
                return "OM Atualizado com sucesso";
            }
        } catch (exception) {
            http_response_code(500);
            return "Houve um problema para atualizar o tipo de documento";
        }
    }

    public function listar()
    {
        header('Content-Type: application/json; charset=utf-8');
        $service = new OMServices();
        var_dump($service->listarOM());
        echo json_encode($service->listarOM());
    }
}
