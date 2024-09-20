<?php

namespace Marinha\Mvc\Models;

class Documentos
{
    private ?int $id;
    private string $docid;
    private string $nip; 
    private int $semestre; 
    private int $ano; 
    private int $tipodocumento; 
    private int $folderid;
    private int $armario;   

    public function __construct(?int $id, string $docid, string $nip, int $semestre, int $ano, int $tipodocumento, int $folderid, int $armario)
    {
        $this->id = $id;
        $this->docid = $docid;
        $this->nip = $nip;
        $this->semestre = $semestre;
        $this->ano = $ano;
        $this->tipodocumento = $tipodocumento;
        $this->folderid = $folderid;
        $this->armario = $armario;
    }
  

    public function id(): ?int
    {
        return $this->id;
    }

    public function docid(): string
    {
        return $this->docid;
    }

    public function nip(): string
    {
        return $this->nip;
    }

    public function semestre(): int
    {
        return $this->semestre;
    }
    public function ano(): int
    {
        return $this->ano;
    }
    public function tipodocumento(): int
    {
        return $this->tipodocumento;
    }
    public function folderid(): int
    {
        return $this->folderid;
    }
    public function armario(): int
    {
        return $this->armario;
    }
}
