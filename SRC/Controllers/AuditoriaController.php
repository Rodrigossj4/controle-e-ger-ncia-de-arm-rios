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
        $service = new AuditoriaServices();
        $LogList = $service->listaDadosAuditoria();

        require __DIR__ . '../../Views/auditoria/index.php';
    }
}
