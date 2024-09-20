<?php

namespace Marinha\Mvc\ValueObjects;

class DocumentoPaginaVO
{
    public int $id;
    public string $docid;
    public string $nip; 
    public int $semestre; 
    public int $ano; 
    public int $tipodocumento; 
    public int $armario; 
    public ?int $idpagina; 
    public int $numpagina;
    public string $arquivo;
    public string $desctipo;
    public string $nomeArmario;
}
