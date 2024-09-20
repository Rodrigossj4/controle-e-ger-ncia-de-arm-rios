<?php

declare(strict_types=1);

namespace Marinha\Mvc\Controllers;

use Marinha\Mvc\Helpers\Helppers;
use Marinha\Mvc\Helpers\Request;

class Controller
{
    public function validarSessao()
    {
        $funcoes = new Helppers();
        if (!$funcoes->validarSessao())
            header("location: /");
    }

    public function retornaIP()
    {
        return Request::get_client_ip();
    }
}
