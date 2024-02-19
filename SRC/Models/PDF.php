<?php

namespace Marinha\Mvc\Models;

use setasign\Fpdi\Tcpdf\Fpdi;
use Exception;
Class PDF extends FPDI{
    private $nome;
    private $cpf;

    function __construct($nome,$cpf){
        parent::__construct();
        $this->nome = $nome;
        $this->cpf = $cpf;

    }

    function Header(){
    }

    function Footer(){
        // Positionnement à 1,5 cm du bas
        $this->SetY(-10);
        // Police Arial italique 8
        $this->SetFont('Helvetica','',6);
        // Numéro de page
        $textoFooter = "DOCUMENTO ASSINADO DIGITALMENTE POR $this->nome CPF $this->cpf VERIFIQUE O DOCUMENTO EM https://verificador.iti.gov.br";
        $this->Cell(0,10,$textoFooter,'T',0,'C');
        }

}
// echo ;

