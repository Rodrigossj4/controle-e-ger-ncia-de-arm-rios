<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\TipoDocumentoService;

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

   public function cadastrar()
   {
      if (strlen(filter_input(INPUT_POST, 'codigo')) < 1 || strlen(filter_input(INPUT_POST, 'nomeInterno')) < 1 || strlen(filter_input(INPUT_POST, 'nomeExterno')) < 1) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      try {
         $armariosList = array();
         array_push($armariosList, array(
            'codigo' => filter_input(INPUT_POST, 'codigo'),
            'nomeinterno' => filter_input(INPUT_POST, 'nomeInterno'),
            'nomeexterno' => filter_input(INPUT_POST, 'nomeExterno')
         ));

         $service = new ArmarioServices();
         if ($service->BuscarArmario($armariosList) > 0) {
            http_response_code(500);
            return "Já existe um armario com esse nome cadastrado";
         }

         if ($service->cadastrarArmarios($armariosList)) {
            http_response_code(200);
            return "Armario Cadastrado";
         }
      } catch (Exception) {
         http_response_code(500);
         return "Houve um problema para cadastrar o armário";
      }
   }

   public function gerenciar()
   {
      $this->validarSessao();
      $service = new TipoDocumentoService();
      //$TipoDocumentoList = $service->listaTipoDocumentoArmario((int)filter_input(INPUT_GET, 'IdArmario'),);
      $TipoDocumentoList = $service->listaTipoDocumento();

      echo json_encode($TipoDocumentoList);
      //require __DIR__ . '../../Views/armarios/gerenciar.php';
   }

   public function vincularDocumentos(): bool
   {
      //$this->validarSessao();
      $service = new ArmarioServices();
      $vinculosList = array();
      array_push($vinculosList, array(
         'id' => filter_input(INPUT_POST, 'IdArmario'),
         'idTipoDocumento' => filter_input(INPUT_POST, 'listarDocumentos')
      ));
      $service->vincularDocumentos($vinculosList);

      return true;
   }

   public function desvincularDocumentos()
   {
      //$this->validarSessao();
      $service = new ArmarioServices();
      $vinculosList = json_decode(file_get_contents('php://input'));
      //var_dump($vinculosList);
      $service->desvincularDocumentos($vinculosList->idTipoDoc, $vinculosList->idArmario);

      return true;
   }
   public function TipoDocsArmarios()
   {
      $this->validarSessao();
      $service = new TipoDocumentoService();
      $vinculosList = array();
      array_push($vinculosList, array(
         'id' => filter_input(INPUT_POST, 'IdArmario')
      ));

      echo json_encode($service->listaTipoDocumentoArmario(filter_input(INPUT_GET, 'IdArmario')));
   }

   public function listar()
   {
      header('Content-Type: application/json; charset=utf-8');
      $service = new ArmarioServices();
      echo json_encode($service->listaArmarios());
   }

   public function alterar()
   {
      if (strlen(filter_input(INPUT_POST, 'id')) < 1 || strlen(filter_input(INPUT_POST, 'codigo')) < 1 || strlen(filter_input(INPUT_POST, 'nomeInterno')) < 1 || strlen(filter_input(INPUT_POST, 'nomeExterno')) < 1) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      try {
         $armariosList = array();
         array_push($armariosList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'codigo' => filter_input(INPUT_POST, 'codigo'),
            'nomeinterno' => filter_input(INPUT_POST, 'nomeInterno'),
            'nomeexterno' => filter_input(INPUT_POST, 'nomeExterno')
         ));

         $service = new ArmarioServices();
         if ($service->alterarArmarios($armariosList)) {
            http_response_code(200);
            return "Armario Atualizado";
         }
         //return true;
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para atualizar o armário";
      }
   }

   public function inativar()
   {
      try {
         $service = new ArmarioServices();
         $tipoDocumentoService = new TipoDocumentoService();

         if ($service->excluirArmario(filter_input(INPUT_POST, 'id'))) {
            http_response_code(200);
            return "Armario excluído Cadastrado";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para excluir o armário";
      }
   }

   public function excluir()
   {
      try {
         $service = new ArmarioServices();
         $tipoDocumentoService = new TipoDocumentoService();

         if (Count($tipoDocumentoService->listaTipoDocumentoArmario(filter_input(INPUT_POST, 'id'))) != 0) {
            http_response_code(500);
            return "Existem Tipos de documentos vinculados a esse armário. Exclua antes.";
         }

         if ($service->excluirArmario(filter_input(INPUT_POST, 'id'))) {
            http_response_code(200);
            return "Armario excluído Cadastrado";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para excluir o armário";
      }
   }
}
