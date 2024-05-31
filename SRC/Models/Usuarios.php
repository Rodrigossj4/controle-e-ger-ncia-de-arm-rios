<?php

namespace Marinha\Mvc\Models;

class Usuarios
{
    private ?int $codUsuario;
    private string $NomeUsuario;
    private string $Nip;
    private string $SenhaUsuario;
    private int $idAcesso;
    private int $om;
    private string $setor;

    public function __construct(?int $codUsuario, string $NomeUsuario, string $Nip, string $SenhaUsuario, int $idAcesso, int $om, $setor)
    {
        $this->codUsuario = $codUsuario;
        $this->NomeUsuario = $NomeUsuario;
        $this->Nip = $Nip;
        $this->SenhaUsuario = $SenhaUsuario;
        $this->idAcesso = $idAcesso;
        $this->om = $om;
        $this->setor = $setor;
    }

    public function codUsuario(): ?int
    {
        return $this->codUsuario;
    }

    public function NomeUsuario(): string
    {
        return $this->NomeUsuario;
    }
    public function Nip(): string
    {
        return $this->Nip;
    }

    public function SenhaUsuario(): string
    {
        return hash('sha256', $this->Nip . $this->SenhaUsuario);
    }

    public function idAcesso(): string
    {
        return $this->idAcesso;
    }

    public function OM(): int
    {
        return $this->om;
    }

    public function setor(): string
    {
        return $this->setor;
    }
}
