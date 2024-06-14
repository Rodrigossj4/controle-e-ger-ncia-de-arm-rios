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

        require __DIR__ . '../../Views/auditoria/index.php';
    }
}
