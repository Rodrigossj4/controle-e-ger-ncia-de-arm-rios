<?php

namespace Marinha\Mvc\Helpers;

class Helppers
{
    public static function validarSessao(): bool
    {
        if (!isset($_SESSION))
            session_start();

        if ($_SESSION['usuario'] === null)
            return false;

        return true;
    }

    public function somenteNumeros($str)
    {
        return preg_replace("/[^0-9]/", "", $str);
    }
}
