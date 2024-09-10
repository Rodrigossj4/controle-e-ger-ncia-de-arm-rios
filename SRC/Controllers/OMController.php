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
        if (strlen(filter_input(INPUT_POST, 'siglaAlter')) < 1 || strlen(filter_input(INPUT_POST, 'nomeOMAlter')) < 1) {
            http_response_code(500);
            return "Todos os campos são obrigatórios";
        }
        try {
            $omList = array();
            array_push($omList, array(
                'codOM' => filter_input(INPUT_POST, 'codOMAlter'),
                'sigla' => filter_input(INPUT_POST, 'siglaAlter'),
                'nomeOM' => filter_input(INPUT_POST, 'nomeOMAlter')
            ));

            $service = new OMServices();
            if ($service->atualizarOM($omList)) {
                http_response_code(200);
                return "OM Atualizado com sucesso";
            }
        } catch (exception) {
            http_response_code(500);
            return "Houve um problema para atualizar OM";
        }
    }

    public function listar()
    {
        header('Content-Type: application/json; charset=utf-8');
        $service = new OMServices();
        echo json_encode($service->listarOM());
    }

    public function excluir()
   {
      try {
            $service = new OMServices();
            $total = count($service->usersOM(filter_input(INPUT_POST, 'codOMExcluir')));
            if ($total > 0) {
                http_response_code(400);
                echo  "Existem Usuários vinculados a essa OM. Exclua antes.";
            }else{
                if ($service->excluirOM(filter_input(INPUT_POST, 'codOMExcluir'))) {
                    http_response_code(200);
                    return "OM excluído com sucesso";
                }
            }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para excluir OM. Verifique se há OM cadastrados com esse tipo.";
      }
   }
}
