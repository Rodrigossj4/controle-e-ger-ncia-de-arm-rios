<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\PerfilAcessoServices;
use Marinha\Mvc\Services\UsuarioServices;
use Marinha\Mvc\Services\ArmarioServices;
use Sabberworm\CSS\Value\Size;

class PerfilAcessoController extends Controller
{
   public function __construct()
   {
   }

   public function index()
   {
      $this->validarSessao();

      if ($_SESSION['usuario'][0]["nivelAcesso"] != 1)
         header("location: /home");

      $service = new PerfilAcessoServices();
      $armarioService = new ArmarioServices();
      $ArmariosList = $armarioService->listaArmarios();

      $PerfilAcessoList = $service->listaPerfis();
      require __DIR__ . '../../Views/perfis/index.php';
   }

   public function cadastrar()
   {
      if (strlen(filter_input(INPUT_POST, 'nomePerfil')) < 1) {
         http_response_code(500);
         echo  "Todos os campos são obrigatórios";
         return false;
      }

      if (empty($_POST['armarios'])) {
         http_response_code(500);
         echo "Selecione um armário";
         return false;
      }


      try {
         $perfilList = array();
         array_push($perfilList, array(
            'nomeperfil' => filter_input(INPUT_POST, 'nomePerfil'),
            'nivelAcesso' => filter_input(INPUT_POST, 'nivelAcesso'),
            'armarios' =>  $_POST['armarios']
         ));

         $service = new PerfilAcessoServices();

         if ($service->BuscarPerfil($perfilList) > 0) {
            http_response_code(500);
            return "Já existe um perfil com esse nome cadastrado";
         }

         if ($service->cadastrarPerfis($perfilList)) {
            http_response_code(200);
            return "Armario Cadastrado";
         }
      } catch (Exception) {
         http_response_code(500);
         return "Houve um problema para cadastrar o novo perfil";
      }
   }

   public function listar()
   {
      header('Content-Type: application/json; charset=utf-8');
      $service = new PerfilAcessoServices();
      echo json_encode($service->listaPerfis());
   }

   public function exibirDadosPerfil()
   {
      $perfil = json_decode(file_get_contents('php://input'));
      header('Content-Type: application/json; charset=utf-8');
      $service = new PerfilAcessoServices();
      echo json_encode($service->exibirDadosPerfil($perfil->codperfil));
   }

   public function alterar()
   {
      if (strlen(filter_input(INPUT_POST, 'nomeperfil')) < 1) {
         http_response_code(500);
         echo  "Todos os campos são obrigatórios";
         return false;
      }

      if (empty($_POST['armariosAlt'])) {
         http_response_code(500);
         echo "Selecione um armário";
         return false;
      }

      try {
         $perfilList = array();
         array_push($perfilList, array(
            'id' => filter_input(INPUT_POST, 'id'),
            'nomeperfil' => filter_input(INPUT_POST, 'nomeperfil'),
            'nivelAcesso' => filter_input(INPUT_POST, 'nivelAcessoAlt'),
            'armarios' =>  $_POST['armariosAlt']
         ));

         $service = new PerfilAcessoServices();

         if (($service->BuscarPerfil($perfilList) > 0) && (filter_input(INPUT_POST, 'nomePerfil') == filter_input(INPUT_POST, 'nomeperfilOriginal'))) {
            http_response_code(500);
            echo "Já existe um perfil com esse nome cadastrado";
            return false;
         }

         if ($service->alterar($perfilList, filter_input(INPUT_POST, 'id'))) {
            http_response_code(200);
            echo "Perfil Alterado";
            return true;
         }
      } catch (Exception) {
         http_response_code(500);
         echo "Houve um problema para alterar os dados do perfil";
         return false;
      }
   }

   public function excluir()
   {
      try {
         $service = new PerfilAcessoServices();
         $Usuarioservice = new UsuarioServices();

         if ($Usuarioservice->totalUsuariosPerfil(filter_input(INPUT_POST, 'id')) != 0) {
            http_response_code(500);
            return "Existem usuários com esse perfil. Exclua antes.";
         }

         if ($service->excluir(filter_input(INPUT_POST, 'id'))) {
            http_response_code(200);
            return "Perfil Cadastrado com sucesso";
         }
      } catch (exception) {
         http_response_code(500);
         return "Houve um problema para excluir o perfil";
      }
   }
}
