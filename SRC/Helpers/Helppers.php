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

    public function validarNip(string $nip): bool
    {
        if (strlen($nip) != 8)
            return false;

        if (!ctype_digit($nip))
            return false;

        if (!$this->NIPValido($nip))
            return false;

        return true;
    }
    public function tratarStringUTF8($string)
    {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }

    public function removerBarraInicialUrl($url): string
    {
        if (strpos($url, '/') === 0) {
            $url = substr($url, 1);
        }
        return $url;
    }
    public function validarSenha($senha): bool
    {
        if (strlen($senha) < 10) {
            return false;
        }

        // Verifica se a senha possui pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial
        if (!preg_match('/[A-Z]/', $senha) || !preg_match('/[a-z]/', $senha) || !preg_match('/[0-9]/', $senha) || !preg_match('/[^A-Za-z0-9]/', $senha)) {
            return false;
        }

        return true;
    }

    function NIPValido($value)
    {
        if (is_object($value)) {
            $value = $value->getNip();
        }
        $isValid = true;

        $value = (string) str_replace('.', '', $value);
        $value = (string) str_replace('-', '', $value);
        $tamanhoNIP = strlen($value);

        $total = ((int) substr($value, 0, 1)) * 8
            + ((int) substr($value, 1, 1)) * 7
            + ((int) substr($value, 2, 1)) * 6
            + ((int) substr($value, 3, 1)) * 5
            + ((int) substr($value, 4, 1)) * 4
            + ((int) substr($value, 5, 1)) * 3
            + ((int) substr($value, 6, 1)) * 2;

        $resto = $total % 11;
        if ($resto == 0) {
            $resto = 1;
        } else {
            if ($resto == 1) {
                $resto = 0;
            } else {
                $resto = 11 - $resto;
            }
        }

        if (!($resto == ((int)substr($value, 7, 1)))) {

            $isValid = false;
        }
        return $isValid;
    }
}
