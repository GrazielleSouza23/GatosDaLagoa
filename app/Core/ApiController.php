<?php

namespace App\Core;

/**
 * Controller base para todos os endpoints da API REST (/api/v1/*).
 * Concentra o acesso ao Request e às respostas JSON padronizadas.
 */
abstract class ApiController
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }
}
