<?php

namespace Marinha\Mvc\Models;

class OM
{
    private ?int $codOM;
    private string $NomeAbreviado;
    private string $NomOM;
    private bool $Ativa;


    public function __construct(?int $codOM, string $NomeAbreviado, string $NomOM, bool $Ativa)
    {
        $this->codOM = $codOM;
        $this->NomeAbreviado = $NomeAbreviado;
        $this->NomOM = $NomOM;
        $this->Ativa = $Ativa;
    }


    public function codOM(): ?int
    {
        return $this->codOM;
    }

    public function nomeAbreviado(): string
    {
        return $this->NomeAbreviado;
    }

    public function nomOM(): string
    {
        return $this->NomOM;
    }

    public function ativa(): string
    {
        return $this->Ativa;
    }
}
