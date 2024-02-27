<?php

declare(strict_types=1);

namespace Marinha\Mvc\Controllers;
use Marinha\Mvc\Helpers\Helppers;
class Controller
{
    public function validarSessao()
    {
        $funcoes = new Helppers();
        if(!$funcoes->validarSessao())
            header("location: /");
    }
   
}
