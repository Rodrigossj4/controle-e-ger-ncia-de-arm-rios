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
        var_dump($nip);
        if (strlen($nip) != 8)
            return false;


        if (!ctype_digit($nip))
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

    function NIPInvalido($RA, $Mens = "")
    {
        // Verifica se o RA foi digitado pela metade
        if (strlen($RA) < 8) {
            if ($Mens == "") {
                echo "Código NIP Incorreto!";
            } else {
                echo "NIP Inválido!";
            }
            return true;
        }

        // Realiza o cálculo de validação do código RA
        $Total = 0;
        $Total += intval($RA[0]) * 8;
        $Total += intval($RA[1]) * 7;
        // Ignora o índice 2, que não está no cálculo original
        $Total += intval($RA[3]) * 6;
        $Total += intval($RA[4]) * 5;
        $Total += intval($RA[5]) * 4;
        $Total += intval($RA[6]) * 3;
        // Ignora o índice 8, que não está no cálculo original
        $Total += intval($RA[8]) * 2;

        $Resto = $Total % 11;
        if ($Resto == 0) {
            $Resto = 1;
        } elseif ($Resto == 1) {
            $Resto = 0;
        } else {
            $Resto = 11 - $Resto;
        }

        if ($RA[9] != strval($Resto)) {
            if ($Mens == "") {
                echo "Código NIP Inválido!";
            } else {
                echo "NIP Inválido!";
            }
            return true;
        } else {
            return false;
        }
    }

    // Exemplo de uso
    //$RA = "123456789";
    //if (NIPInvalido($RA)) {
    // Senão há mensagem personalizada definida, apenas apresenta a mensagem padrão
    //} else {
    // RA válido
    //}
}
