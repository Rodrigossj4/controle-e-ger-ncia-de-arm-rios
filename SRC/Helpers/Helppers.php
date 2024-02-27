<?php
namespace Marinha\Mvc\Helpers;

class Helppers{
    public static function validarSessao():bool
    {
        if (!isset($_SESSION))
            session_start();
        
        if($_SESSION['usuario'] === null)
            return false;

        return true;
    }
}

