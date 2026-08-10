<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\Configuracao;

/**
 * GET  /api/v1/configuracoes
 * GET  /api/v1/configuracoes/{chave}
 * PUT  /api/v1/configuracoes/{chave}   (autenticado)
 */
class ConfiguracaoApiController extends ApiController
{
    public function index(): void
    {
        Response::success(Configuracao::allList());
    }

    public function show(string $chave): void
    {
        $config = Configuracao::findByChave($chave);
        if (!$config) {
            Response::error('Configuração não encontrada.', 404);
        }
        Response::success($config);
    }

    public function update(string $chave): void
    {
        Auth::requireApiAuth();

        $valor = $this->request->input('valor');
        if ($valor === null) {
            Response::error('Informe o campo "valor".', 422);
        }

        Configuracao::set($chave, sanitize((string) $valor));
        Response::success(Configuracao::findByChave($chave), 200, 'Configuração atualizada.');
    }
}
