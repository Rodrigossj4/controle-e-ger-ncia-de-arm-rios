<?php

namespace Marinha\Mvc\Models;

class PerfilAcesso{
    private ?int $id;
    private string $nomePerfil;

    public function __construct(?int $id, string $nomePerfil)
    {
        $this->id = $id;
        $this->nomePerfil= $nomePerfil;        
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function nomePerfil(): string
    {
        return $this->nomePerfil;
    }
}