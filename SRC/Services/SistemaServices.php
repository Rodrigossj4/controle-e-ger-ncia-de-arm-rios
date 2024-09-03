<?php

namespace Marinha\Mvc\Services;

use Marinha\Mvc\Infra\Repository\Conexao;
use Exception;
use Marinha\Mvc\Infra\Repository\SistemaRepository;

class SistemaServices
{
    protected $key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    protected $diretorio = "/marinha/sisimagem/";
    protected $diretorioLote = "documentos/";
    public function Conexao()
    {
        return Conexao::createConnection();
    }

    public function gravarLogOperacoes(array $log)
    {
        try {
            $repository = new SistemaRepository($this->Conexao());
            return $repository->gravarLogOperacoes($log);
        } catch (Exception $e) {
            echo $e;
        }
    }

    function removeDirectory($dir)
    {
        exec('rm -rf ' . escapeshellarg($dir));

        //exec('rmdir /S /Q ' . escapeshellarg($dir));
    }
}
