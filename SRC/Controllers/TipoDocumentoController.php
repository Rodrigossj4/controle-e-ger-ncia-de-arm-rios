<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Models\TipoDocumento;
use Marinha\Mvc\Services\ArmarioServices;
use Marinha\Mvc\Services\TipoDocumentoService;
use Marinha\Mvc\Services\DocumentoServices;

class TipoDocumentoController extends Controller
{

   public function __construct()
   {
   }

   public function index()
   {
      $this->validarSessao();
      $service = new TipoDocumentoService();
      $armariosService =  new ArmarioServices();
      $TipoDocumentoList = $service->listaTipoDocumento();
      $ArmariosList = $armariosService->listaArmarios();
      require __DIR__ . '../../Views/tipoDocumento/index.php';
   }

   public function cadastrarTipodocumento()
   {
      if (strlen(filter_input(INPUT_POST, 'desctipo')) < 1) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      try {
         $tipoDocList = array();
         array_push($tipoDocList, array(
            'DescTipoDoc' => filter_input(INPUT_POST, 'desctipo')
         ));

         $service = new TipoDocumentoService();

         if ($service->BuscarTipoDocumento($tipoDocList) > 0) {
            http_response_code(500);
            return "Já existe um Tipo de documento com esse nome cadastrado";
         }

         if ($service->cadastrarTipoDocumento($tipoDocList)) {
            http_response_code(200);
            return "Tipo de documento cadastrado com sucesso.";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para cadastrar o tipo de documento";
      }
   }

   public function listar()
   {
      header('Content-Type: application/json; charset=utf-8');
      $service = new TipoDocumentoService();
      echo json_encode($service->listaTipoDocumento());
   }

   public function listarTipoDocumentoArmarios()
   {
      try {
         $idArmario = filter_input(INPUT_GET, 'id');
         header('Content-Type: application/json; charset=utf-8');
         $service = new TipoDocumentoService();
         //var_dump("teste");
         echo json_encode($service->listaTipoDocumentoArmario($idArmario));
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema";
      }
   }

   public function listarTipoDocumentoNaoPertencentesArmarios()
   {
      $idArmario = filter_input(INPUT_GET, 'id');
      //var_dump($idArmario);
      header('Content-Type: application/json; charset=utf-8');
      $service = new TipoDocumentoService();
      //var_dump($service->listarTipoDocumentoNaoPertencentesArmario($idArmario));
      echo json_encode($service->listarTipoDocumentoNaoPertencentesArmario($idArmario));
   }
   public function alterar()
   {
      if (strlen(filter_input(INPUT_POST, 'descTipoDoc')) < 1) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      try {
         $tipoDocList = array();
         array_push($tipoDocList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'desctipo' => filter_input(INPUT_POST, 'descTipoDoc'),
            'armario' => filter_input(INPUT_POST, 'armario')
         ));

         $service = new TipoDocumentoService();

         if ($service->alterarTipoDoc($tipoDocList)) {
            http_response_code(200);
            return "Tipo de documento cadastrado com sucesso.";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para atualizar o tipo de documento";
      }
   }

   public function excluir()
   {
      try {
         $service = new TipoDocumentoService();
         $serviceDocumento = new DocumentoServices();

         $total = count($service->listaArmarioTipoDocumento(filter_input(INPUT_POST, 'id')));

         if ($total > 0) {
            http_response_code(500);
            return "Existem Armários vinculados a esse tipo de documento. Exclua antes.";
         }

         if (Count($serviceDocumento->BuscarDocumentosPorTipo(filter_input(INPUT_POST, 'id'))) != 0) {
            http_response_code(500);
            return "Existem documentos desse tipo cadastrados. Exclua antes.";
         }

         if ($service->excluirTipoDocumento(filter_input(INPUT_POST, 'id'))) {
            http_response_code(200);
            return "Tipo de documento excluído com sucesso";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para excluir o tipo de documento";
      }
   }
}
