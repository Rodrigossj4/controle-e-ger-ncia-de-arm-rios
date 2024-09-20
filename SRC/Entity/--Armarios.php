<?php

namespace Marinha\Mvc\Enity;

class Armarios
{
    private ?int $id;
    private string $codigo;
    private string $nomeInterno; 
    private string $nomeExterno; 
 

    public function __construct(?int $id, string $codigo, string $nomeInterno, string $nomeExterno)
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nomeInterno = $nomeInterno;
        $this->nomeExterno = $nomeExterno;
    }
  

    public function id(): ?int
    {
        return $this->id;
    }

    public function codigo(): string
    {
        return $this->codigo;
    }

    public function nomeInterno(): string
    {
        return $this->nomeInterno;
    }

    public function nomeExterno(): string
    {
        return $this->nomeExterno;
    }
}
