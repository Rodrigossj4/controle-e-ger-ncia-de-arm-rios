<?php

namespace Marinha\Mvc\Models;

class LogOperacoes
{
    private ?int $id;
    private int $IdOperacao;
    private int $IdUsuario; 
    private string $dh; 
    private int $IdArmario;
 

    public function __construct(?int $id, int $IdOperacao, int $IdUsuario, string $dh, int $IdArmario)
    {
        $this->id = $id;
        $this->IdOperacao = $IdOperacao;
        $this->IdUsuario = $IdUsuario;
        $this->dh = $dh;
        $this->IdArmario= $IdArmario;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function idOperacao(): int
    {
        return $this->IdOperacao;
    }
    public function idUsuario(): int
    {
        return $this->IdUsuario;
    }
    public function dh(): string
    {
        return $this->dh;
    }

    public function idArmario(): int
    {
        return $this->IdArmario;
    }
}