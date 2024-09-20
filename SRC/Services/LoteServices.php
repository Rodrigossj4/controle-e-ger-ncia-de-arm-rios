<?php

namespace Marinha\Mvc\Services;

use Exception;


use Marinha\Mvc\Infra\Repository\LotesRepository;


class LoteServices extends SistemaServices
{
    private $diretorio = "/marinha/lotes/";
    public function __construct()
    {
    }

    public function listarLotes(): array
    {
        try {
            $repository = new LotesRepository($this->Conexao());
            return $repository->listarLotes();
        } catch (Exception $e) {
            echo $e;
            return [];
        }
    }

    public function cadastrar(array $lote): bool
    {
        try {
            $repository = new LotesRepository($this->Conexao());
            $retorno = $repository->cadastrar($lote);
            
            if($retorno)
                $this->subirArquivos($lote[0]["pasta"]);

            return $retorno;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function gerarPastaLote(int $idPasta): string
    {       
        mkdir("{$this->diretorio}{$idPasta}", 0777, true);
        return "{$this->diretorio}{$idPasta}";
    }

    private function subirArquivos(string $diretorio){
        $total = count($_FILES['documento']['name']);

        for ($i = 0; $i < $total; $i++) {
             $caminhoArqImgServ = $diretorio ."/". $_FILES['documento']['name'][$i];
          
             $this->uploadImgPasta($caminhoArqImgServ, $i);          
        }
    }
    private function uploadImgPasta(string $caminhoArqImg, int $indice)
    {         
        move_uploaded_file($_FILES['documento']['tmp_name'][$indice],  $caminhoArqImg);       
    }
}