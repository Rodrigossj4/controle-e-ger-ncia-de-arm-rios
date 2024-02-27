<?php

namespace Marinha\Mvc\Services;

use Marinha\Mvc\Infra\Repository\Conexao;

use Marinha\Mvc\Infra\Repository\LoginRepository;
class SistemaServices
{
    public function Conexao(){
        return Conexao::createConnection();
    }
}