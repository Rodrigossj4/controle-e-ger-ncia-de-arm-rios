<?php

namespace Marinha\Mvc\Models;

class Operacoes
{
    private ?int $IdOperacao;
    private string $CodOperacao;
    private string $DescOperacao;

    public function __construct(?int $IdOperacao, string $CodOperacao, string $DescOperacao)
    {
        $this->IdOperacao = $IdOperacao;
        $this->CodOperacao = $CodOperacao;
        $this->DescOperacao = $DescOperacao;
    }

    public function idOperacao(): ?int
    {
        return $this->IdOperacao;
    }

    public function codoperacao(): string
    {
        return $this->CodOperacao;
    }
    public function idusuario(): string
    {
        return $this->DescOperacao;
    }
}
