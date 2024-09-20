<?php

namespace Marinha\Mvc\Models;

class PerfilAcesso
{
    private ?int $id;
    private string $nomePerfil;

    private int $nivelAcesso;

    public function __construct(?int $id, string $nomePerfil, int $nivelAcesso)
    {
        $this->id = $id;
        $this->nomePerfil = $nomePerfil;
        $this->nivelAcesso = $nivelAcesso;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function nomePerfil(): string
    {
        return $this->nomePerfil;
    }

    public function nivelAcesso(): int
    {
        return $this->nivelAcesso;
    }
}
