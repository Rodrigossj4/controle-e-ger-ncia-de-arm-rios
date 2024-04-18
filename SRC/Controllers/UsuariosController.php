<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\UsuarioServices;
use Marinha\Mvc\Services\PerfilAcessoServices;
use Marinha\Mvc\Helpers;
use Marinha\Mvc\Helpers\Helppers;

class UsuariosController  extends Controller
{

   public function __construct()
   {
   }

   public function index()
   {
      $this->validarSessao();
      $service = new UsuarioServices();
      $perfilService = new PerfilAcessoServices();

      $PerfilAcessoList = $perfilService->listaPerfis();
      $UsuariosList = $service->listaUsuarios();

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
         return "Nip inválido";
      }

      if (!$funcoes->validarSenha(filter_input(INPUT_POST, 'senhausuario'))) {
         http_response_code(500);
         return "Senha inválida";
      }      

      try {

         $usuariosList = array();;
         array_push($usuariosList, array(
            'codusuario' => filter_input(INPUT_POST, 'codusuario'),
            'nomeusuario' => filter_input(INPUT_POST, 'nomeusuario'),
            'nip' => $funcoes->somenteNumeros(filter_input(INPUT_POST, 'nip')),
            'senhausuario' => filter_input(INPUT_POST, 'senhausuario'),
            'idacesso' => filter_input(INPUT_POST, 'idacesso')
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

   public function alterar(): bool
   {
      $usuariosList = array();
      array_push($usuariosList, array(
         'codusuario' => filter_input(INPUT_POST, 'id'),
         'nomeusuario' => filter_input(INPUT_POST, 'nomeusuario')
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
