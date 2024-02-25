<?php

namespace Marinha\Mvc\Models;

class Login
{   
    private string $nip;
    private string $senha; 
    private string $nomeusuario;
    private int $idacesso; 
 
    public function __construct(int $nip, string $senha, string $nomeusuario, int $idacesso)
    {
        $this->nip = $nip;
        $this->senha = $senha;
        $this->nomeusuario = $nomeusuario;
        $this->idacesso = $idacesso;
    }

    public function nip(): string
    {
        return $this->nip;
    }

    public function senha(): string
    {
        return $this->senha;
    }

    public function nomeusuario(): string
    {
        return $this->nomeusuario;
    }

    public function idacesso(): int
    {
        return $this->idacesso;
    }
}