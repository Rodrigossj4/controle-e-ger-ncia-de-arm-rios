<?php

namespace Marinha\Mvc\Models;

class Lotes
{
    private ?int $id;
    private string $numeroLote;
    private string $pasta; 
    private bool $ativo; 
 

    public function __construct(?int $id, string $numeroLote, string $pasta, bool $ativo)
    {
        $this->id = $id;
        $this->numeroLote = $numeroLote;
        $this->pasta = $pasta;
        $this->ativo= $ativo;
    }
  

    public function id(): ?int
    {
        return $this->id;
    }

    public function numeroLote(): string
    {
        return $this->numeroLote;
    }

    public function pasta(): string
    {
        return $this->pasta;
    }

    public function ativo(): bool
    {
        return $this->ativo;
    }
}
