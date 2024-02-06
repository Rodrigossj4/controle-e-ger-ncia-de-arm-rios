<?php

namespace Marinha\Mvc\Models;

class TipoDocumento{
    private ?int $id;
    private string $descTipo;

    public function __construct(?int $id, string $descTipo)
    {
        $this->id = $id;
        $this->descTipo = $descTipo;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function descTipo(): string
    {
        return $this->descTipo;
    }
}