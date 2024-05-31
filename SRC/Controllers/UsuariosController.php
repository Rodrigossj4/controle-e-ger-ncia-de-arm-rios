<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\UsuarioServices;
use Marinha\Mvc\Services\PerfilAcessoServices;
use Marinha\Mvc\Services\OMServices;
use Marinha\Mvc\Helpers;
use Marinha\Mvc\Helpers\Helppers;

class UsuariosController  extends Controller
{

   public function __construct()
   {
   }

   public function index()
   {
      //$this->validarSessao();
      $service = new UsuarioServices();
      $perfilService = new PerfilAcessoServices();
      $omService = new OMServices();

      $PerfilAcessoList = $perfilService->listaPerfis();
      $UsuariosList = $service->listaUsuarios();
      $OMList = $omService->listarOM();

      require __DIR__ . '../../Views/usuarios/index.php';
   }

   public function cadastrar()
   {
      $funcoes = new Helppers();

      if (strlen(filter_input(INPUT_POST, 'nomeusuario')) < 1 || strlen(filter_input(INPUT_POST, 'nip')) < 1 || strlen(filter_input(INPUT_POST, 'senhausuario')) < 1 || filter_input(INPUT_POST, 'idacesso') == 0) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      if (!$funcoes->validarNip($funcoes->somenteNumeros(filter_input(INPUT_POST, 'nip')))) {
         http_response_code(500);
         echo "Nip inválido";
         return false;
      }

      if (!$funcoes->validarSenha(filter_input(INPUT_POST, 'senhausuario'))) {
         http_response_code(500);
         echo "Senha inválida";
         return false;
      }

      try {

         $usuariosList = array();;
         array_push($usuariosList, array(
            'codusuario' => filter_input(INPUT_POST, 'codusuario'),
            'nomeusuario' => filter_input(INPUT_POST, 'nomeusuario'),
            'nip' => $funcoes->somenteNumeros(filter_input(INPUT_POST, 'nip')),
            'senhausuario' => filter_input(INPUT_POST, 'senhausuario'),
            'idacesso' => filter_input(INPUT_POST, 'idacesso'),
            'om' => filter_input(INPUT_POST, 'om'),
            'setor' => filter_input(INPUT_POST, 'setor')
         ));

         $service = new UsuarioServices();

         if ($service->cadastrarUsuario($usuariosList)) {
            http_response_code(200);
            return "Usuário Cadastrado";
         }
      } catch (Exception) {
         http_response_code(500);
         return "Houve um problema para cadastrar o usuário";
      }
   }
   public function listar()
   {
      header('Content-Type: application/json; charset=utf-8');
      $service = new UsuarioServices();
      echo json_encode($service->listaUsuarios());
   }

   public function validarNIP(): bool
   {
      $nip = filter_input(INPUT_GET, 'nip');
      $service = new UsuarioServices();
      return $service->validarNIP($nip);
   }

   public function alterar(): bool
   {
      $arquivo = json_decode(file_get_contents('php://input'));
      //unlink($b64->arquivoOriginal);

      $usuariosList = array();
      array_push($usuariosList, array(
         'codusuario' => $arquivo->codusuario,
         'nomeusuario' => $arquivo->nomeusuario
         /*'nip' => filter_input(INPUT_POST, 'nip'),
            'senhausuario' => filter_input(INPUT_POST, 'senhausuario'),
            'idacesso' => filter_input(INPUT_POST, 'idacesso')*/
      ));

      $service = new UsuarioServices();
      $service->alterarUsuario($usuariosList);
      return true;
   }

   public function excluir(): bool
   {
      $service = new UsuarioServices();
      $service->excluirUsuario(filter_input(INPUT_POST, 'id'));
      return true;
   }
}
