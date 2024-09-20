<?php

namespace Marinha\Mvc\Models;

use DateTime;

class MetaTags
{
    private ?int $idMetadados;
    private string $Assunto;
    private string $Autor;
    private DateTime $DataDigitalizacao;
    private string $IdentDocDigital;
    private string $RespDigitalizacao;
    private string $Titulo;
    private string $TipoDocumento;
    private string $Hash;
    private string $Classe;
    private DateTime $DataProdDoc;
    private string $DestinacaoDoc;
    private string $Genero;
    private string $PrazoGuarda;
    private string $Observacoes;
    private int $IdDocumento;
    private int $IdPagina;

    public function __construct(
        ?int $idMetadados,
        string $Assunto,
        string $Autor,
        DateTime $DataDigitalizacao,
        string $IdentDocDigital,
        string $RespDigitalizacao,
        string $Titulo,
        string $TipoDocumento,
        string $Hash,
        string $Classe,
        DateTime $DataProdDoc,
        string $DestinacaoDoc,
        string $Genero,
        string $PrazoGuarda,
        string $Observacoes,
        int $IdDocumento,
        int $IdPagina
    ) {
        $this->idMetadados = $idMetadados;
        $this->Assunto = $Assunto;
        $this->Autor = $Autor;
        $this->DataDigitalizacao = $DataDigitalizacao;
        $this->IdentDocDigital = $IdentDocDigital;
        $this->RespDigitalizacao = $RespDigitalizacao;
        $this->Titulo = $Titulo;
        $this->TipoDocumento = $TipoDocumento;
        $this->Hash = $Hash;
        $this->Classe = $Classe;
        $this->DataProdDoc = $DataProdDoc;
        $this->DestinacaoDoc = $DestinacaoDoc;
        $this->Genero = $Genero;
        $this->PrazoGuarda = $PrazoGuarda;
        $this->Observacoes = $Observacoes;
        $this->IdDocumento = $IdDocumento;
        $this->IdPagina = $IdPagina;
    }

    public function getIdMetadados(): ?int
    {
        return $this->idMetadados;
    }

    public function setIdMetadados(?int $idMetadados): void
    {
        $this->idMetadados = $idMetadados;
    }

    public function getAssunto(): string
    {
        return $this->Assunto;
    }

    public function setAssunto(string $Assunto): void
    {
        $this->Assunto = $Assunto;
    }

    public function getAutor(): string
    {
        return $this->Autor;
    }

    public function setAutor(string $Autor): void
    {
        $this->Autor = $Autor;
    }

    public function getDataDigitalizacao(): DateTime
    {
        return $this->DataDigitalizacao;
    }

    public function setDataDigitalizacao(DateTime $DataDigitalizacao): void
    {
        $this->DataDigitalizacao = $DataDigitalizacao;
    }

    public function getIdentDocDigital(): string
    {
        return $this->IdentDocDigital;
    }

    public function setIdentDocDigital(string $IdentDocDigital): void
    {
        $this->IdentDocDigital = $IdentDocDigital;
    }

    public function getRespDigitalizacao(): string
    {
        return $this->RespDigitalizacao;
    }

    public function setRespDigitalizacao(string $RespDigitalizacao): void
    {
        $this->RespDigitalizacao = $RespDigitalizacao;
    }

    public function getTitulo(): string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): void
    {
        $this->Titulo = $Titulo;
    }

    public function getTipoDocumento(): string
    {
        return $this->TipoDocumento;
    }

    public function setTipoDocumento(string $TipoDocumento): void
    {
        $this->TipoDocumento = $TipoDocumento;
    }

    public function getHash(): string
    {
        return $this->Hash;
    }

    public function setHash(string $Hash): void
    {
        $this->Hash = $Hash;
    }

    public function getClasse(): string
    {
        return $this->Classe;
    }

    public function setClasse(string $Classe): void
    {
        $this->Classe = $Classe;
    }

    public function getDataProdDoc(): DateTime
    {
        return $this->DataProdDoc;
    }

    public function setDataProdDoc(DateTime $DataProdDoc): void
    {
        $this->DataProdDoc = $DataProdDoc;
    }

    public function getDestinacaoDoc(): string
    {
        return $this->DestinacaoDoc;
    }

    public function setDestinacaoDoc(string $DestinacaoDoc): void
    {
        $this->DestinacaoDoc = $DestinacaoDoc;
    }

    public function getGenero(): string
    {
        return $this->Genero;
    }

    public function setGenero(string $Genero): void
    {
        $this->Genero = $Genero;
    }

    public function getPrazoGuarda(): string
    {
        return $this->PrazoGuarda;
    }

    public function setPrazoGuarda(string $PrazoGuarda): void
    {
        $this->PrazoGuarda = $PrazoGuarda;
    }

    public function getObservacoes(): string
    {
        return $this->Observacoes;
    }

    public function setObservacoes(string $Observacoes): void
    {
        $this->Observacoes = $Observacoes;
    }

    public function getIdDocumento(): int
    {
        return $this->IdDocumento;
    }

    public function setIdDocumento(int $IdDocumento): void
    {
        $this->IdDocumento = $IdDocumento;
    }

    public function getIdPagina(): int
    {
        return $this->IdPagina;
    }

    public function setIdPagina(int $IdPagina): void
    {
        $this->IdPagina = $IdPagina;
    }
}
