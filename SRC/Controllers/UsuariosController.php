<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\UsuarioServices;
use Marinha\Mvc\Services\PerfilAcessoServices;
use Marinha\Mvc\Services\OMServices;
use Marinha\Mvc\Services\LoginServices;
use Marinha\Mvc\Helpers;
use Marinha\Mvc\Helpers\Helppers;

class UsuariosController  extends Controller
{

   public function __construct() {}

   public function index()
   {
      $this->validarSessao();

      if ($_SESSION['usuario'][0]["nivelAcesso"] != 1)
         header("location: /home");
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
      $service = new UsuarioServices();

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

      if ($service->BuscarUsuarioNip(filter_input(INPUT_POST, 'nip')) > 0) {
         http_response_code(500);
         echo "Já existe um usuario com esse nip cadastrado";
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

   public function alterar()
   {
      $arquivo = json_decode(file_get_contents('php://input'));
      $funcoes = new Helppers();
      $service = new UsuarioServices();

      if (strlen($arquivo->nomeusuario) < 1 || strlen($arquivo->nipusuario) < 1 || strlen($arquivo->setorusuario) < 1 || $arquivo->acessousuario == 0) {
         http_response_code(500);
         return "Todos os campos são obrigatórios";
      }

      if (!$funcoes->validarNip($funcoes->somenteNumeros($arquivo->nipusuario))) {
         http_response_code(500);
         echo "Nip inválido";
         return false;
      }

      if (($service->BuscarUsuarioNip($arquivo->nipusuario) > 0) && ($arquivo->nipusuario != $arquivo->nipusuariooriginal)) {
         http_response_code(500);
         echo "Já existe um usuario com esse nip cadastrado";
         return false;
      }
      try {
         $usuariosList = array();
         array_push($usuariosList, array(
            'codusuario' => $arquivo->codusuario,
            'nomeusuario' => $arquivo->nomeusuario,
            'nipusuario' => $funcoes->somenteNumeros($arquivo->nipusuario),
            'nipusuariooriginal' => $arquivo->nipusuariooriginal,
            'omusuario' => $arquivo->omusuario,
            'idacesso' => $arquivo->acessousuario,
            'setorusuario' => $arquivo->setorusuario
         ));

         if ($service->alterarUsuario($usuariosList)) {
            http_response_code(200);
            return "Usuário Alterado";
         }
      } catch (Exception) {
         http_response_code(500);
         return "Houve um problema para cadastrar o usuário";
      }
   }

   public function excluir(): bool
   {
      $service = new UsuarioServices();
      session_start();
      if ($_SESSION['usuario'][0]["codusuario"] == filter_input(INPUT_POST, 'id')) {
         http_response_code(500);
         echo "Usuário logado na sessão atual.";
         return false;
      };

      $service->excluirUsuario(filter_input(INPUT_POST, 'id'));
      return true;
   }

   public function trocaSenha()
   {
      require __DIR__ . '../../Views/login/trocaSenha.php';
   }

   public function alterarSenha()
   {
      $funcoes = new Helppers();
      $service = new UsuarioServices();

      if (!$funcoes->validarSenha(filter_input(INPUT_POST, 'novaSenha'))) {
         http_response_code(500);
         echo "A nova senha digitada não segue os padrões solicitados.";
         return false;
      }

      if (!$funcoes->validarSenha(filter_input(INPUT_POST, 'confNovaSenha'))) {
         http_response_code(500);
         echo "A confirmação da nova senha digitada não segue os padrões solicitados.";
         return false;
      }

      if (filter_input(INPUT_POST, 'confNovaSenha') != filter_input(INPUT_POST, 'novaSenha')) {
         http_response_code(500);
         echo "A nova senha é diferente da confirmação de senha digitada.";
         return false;
      }

      if ((filter_input(INPUT_POST, 'senhaAtual') == filter_input(INPUT_POST, 'novaSenha')) || (filter_input(INPUT_POST, 'senhaAtual') == filter_input(INPUT_POST, 'confNovaSenha'))) {
         http_response_code(500);
         echo "A nova senha não pode ser igual a senha atual.";
         return false;
      }

      if ((!$funcoes->validarNip($funcoes->somenteNumeros(filter_input(INPUT_POST, 'nipAltSenha'))))) {
         http_response_code(500);
         echo "Nip inválido. ";
         return false;
      }

      if (!$funcoes->validarSenha(filter_input(INPUT_POST, 'senhaAtual'))) {
         http_response_code(500);
         echo "A Senha atual digitada é inválida.";
         return false;
      }


      $dadosUsuarios = array();
      array_push($dadosUsuarios, array(
         'senha' => filter_input(INPUT_POST, 'senhaAtual'),
         'novaSenha' => filter_input(INPUT_POST, 'novaSenha'),
         'confNovaSenha' => filter_input(INPUT_POST, 'confNovaSenha'),
         'nip' => strlen(filter_input(INPUT_POST, 'nip')) < 1 ? filter_input(INPUT_POST, 'nipAltSenha') : filter_input(INPUT_POST, 'nip'),
         'idUsuario' => 0
      ));

      $idUsuario =  $service->validarSenhaUsuario($dadosUsuarios);
      if (($idUsuario == null) || ($idUsuario == 0)) {
         //http_response_code(500);
         echo "Verifique a senha e o usuário atual";
         return false;
      }

      $dadosUsuarios[0]["idUsuario"] = $idUsuario;

      echo json_encode($service->AlterarSenhaUsuario($dadosUsuarios));
   }

   public function buscarUsuarioPorID()
   {
      header('Content-Type: application/json; charset=utf-8');
      $usuario = json_decode(file_get_contents('php://input'));

      $service = new UsuarioServices();
      $usuario = $service->buscarUsuarioPorID($usuario->codusuario);

      echo json_encode($usuario);
   }

   public function ResetSenhaUsuario(): bool
   {
      $service = new UsuarioServices();
      $service->ResetSenhaUsuario(filter_input(INPUT_POST, 'idSenhaPadrao'));
      return true;
   }
}
