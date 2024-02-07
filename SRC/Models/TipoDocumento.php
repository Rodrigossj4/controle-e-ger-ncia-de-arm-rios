<?php

namespace Marinha\Mvc\Models;

class TipoDocumento{
    private ?int $id;
    private string $descTipo;
    private int $armario;

    public function __construct(?int $id, string $descTipo, int $armario)
    {
        $this->id = $id;
        $this->descTipo = $descTipo;
        $this->armario = $armario;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function descTipo(): string
    {
        return $this->descTipo;
    }

    public function armario(): ?int
    {
        return $this->armario;
    }
}