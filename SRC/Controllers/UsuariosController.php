<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\UsuarioServices;
use Marinha\Mvc\Services\PerfilAcessoServices;

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

    public function cadastrar():bool
     {       
        $usuariosList = array();
        array_push($usuariosList, array(
            'codusuario' => filter_input(INPUT_POST, 'codusuario'),
            'nomeusuario' => filter_input(INPUT_POST, 'nomeusuario'),
            'nip' => filter_input(INPUT_POST, 'nip'),
            'senhausuario' => filter_input(INPUT_POST, 'senhausuario'),
            'idacesso' => filter_input(INPUT_POST, 'idacesso')
        ));

        $service = new UsuarioServices();
        
        return $service->cadastrarUsuario($usuariosList);      
     }
     public function listar()
     {
        header('Content-Type: application/json; charset=utf-8');     
        $service = new UsuarioServices();
        echo json_encode($service->listaUsuarios()); 
     }

     public function alterar():bool
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

     public function excluir():bool
     {        
        $service = new UsuarioServices();
        $service->excluirUsuario(filter_input(INPUT_POST, 'id'));
        return true;
     }
}