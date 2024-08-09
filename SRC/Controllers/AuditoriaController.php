<?php


namespace Marinha\Mvc\Controllers;

use Exception;
use Marinha\Mvc\Services\AuditoriaServices;

class AuditoriaController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $this->validarSessao();

        if ($_SESSION['usuario'][0]["nivelAcesso"] != 1)
            header("location: /home");

        $service = new AuditoriaServices();
        $LogList = $service->listaDadosAuditoria();
        $OperacoesList = $service->ListarOperacoes();
        require __DIR__ . '../../Views/auditoria/index.php';
    }


    public function BuscarLogs(): array
    {
        $ListaLogs = json_decode(file_get_contents('php://input'));

        try {
            $service = new AuditoriaServices();
            return $service->BuscarLogs($ListaLogs);
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function ListarOperacoes(): array
    {
        try {
            $service = new AuditoriaServices();
            return $service->ListarOperacoes();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }
}
