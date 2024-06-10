<?php

namespace Marinha\Mvc\Models;

class LogOperacoes
{
    private ?int $IdLog;
    private string $CodOperacao;
    private int $IdUsuario;
    private string $datahoraoperacao;
    private ?int $IdDocumento;
    private string $IpAcesso;

    public function __construct(?int $IdLog, string $CodOperacao, int $IdUsuario, string $datahoraoperacao, ?int $IdDocumento, string $IpAcesso)
    {
        $this->IdLog = $IdLog;
        $this->CodOperacao = $CodOperacao;
        $this->IdUsuario = $IdUsuario;
        $this->datahoraoperacao = $datahoraoperacao;
        $this->IdDocumento = $IdDocumento;
        $this->IpAcesso = $IpAcesso;
    }

    public function idlog(): ?int
    {
        return $this->IdLog;
    }

    public function codoperacao(): string
    {
        return $this->CodOperacao;
    }
    public function idusuario(): int
    {
        return $this->IdUsuario;
    }
    public function datahoraoperacao(): string
    {
        return $this->datahoraoperacao;
    }

    public function iddocumento(): ?int
    {
        return $this->IdDocumento;
    }

    public function ipacesso(): string
    {
        return $this->IpAcesso;
    }
}
