<?php

declare(strict_types=1);

namespace Marinha\Mvc\Controllers;

class Error404Controller implements Controller
{
    public function processaRequisicao(): void
    {
        http_response_code(201);
    }
}
