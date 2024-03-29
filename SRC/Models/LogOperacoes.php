<?php

namespace Marinha\Mvc\Models;

class LogOperacoes
{
    private ?int $id;
    private string $Codoperacao;
    private int $IdUsuario; 
    private string $dh; 
    private ?int $IdDocumento;
 

    public function __construct(?int $id, string $Codoperacao, int $IdUsuario, string $dh, ?int $IdDocumento)
    {
        $this->id = $id;
        $this->Codoperacao = $Codoperacao;
        $this->IdUsuario = $IdUsuario;
        $this->dh = $dh;
        $this->IdDocumento= $IdDocumento;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function codoperacao(): string
    {
        return $this->Codoperacao;
    }
    public function idUsuario(): int
    {
        return $this->IdUsuario;
    }
    public function dh(): string
    {
        return $this->dh;
    }

    public function idDocumento(): ?int
    {
        return $this->IdDocumento;
    }
}