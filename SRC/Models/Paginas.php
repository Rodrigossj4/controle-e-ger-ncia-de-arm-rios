<?php

namespace Marinha\Mvc\Models;

class paginas
{
    private ?int $id;
    private int $documentoid;
    private string $volume;
    private int $numpagina;
    private string $arquivo;
    private int $codexp;
    private string $filme;
    private string $fotograma;
    private string $imgencontrada;

    public function __construct(?int $id, int $documentoid, string $volume, int $numpagina, string $arquivo, int $codexp, string $filme, string $fotograma, string $imgencontrada)
    {
        $this->id = $id;
        $this->documentoid = $documentoid;
        $this->volume = $volume;
        $this->numpagina = $numpagina;
        $this->arquivo = $arquivo;
        $this->codexp = $codexp;
        $this->filme = $filme;
        $this->fotograma = $fotograma;
        $this->imgencontrada = $imgencontrada;      
    }

    public function id(): int
    {
        return $this->id;
    }
    public function documentoid(): string
    {
        return $this->documentoid;
    }

    public function numpagina(): int
    {
        return $this->numpagina;
    }

    public function arquivo(): string
    {
        return $this->arquivo;
    }
    public function codexp(): string
    {
        return $this->codexp;
    }
    public function filme(): string
    {
        return $this->filme;
    }
    public function imgencontrada(): string
    {
        return $this->imgencontrada;
    }

    public function volume(): string
    {
        return $this->volume;
    }

    public function fotograma(): string
    {
        return $this->fotograma;
    }

}