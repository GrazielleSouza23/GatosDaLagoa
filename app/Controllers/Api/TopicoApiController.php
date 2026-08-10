<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\TopicoAdocao;

/**
 * GET    /api/v1/topicos-adocao
 * POST   /api/v1/topicos-adocao        (autenticado)
 * PUT    /api/v1/topicos-adocao/{id}   (autenticado)
 * DELETE /api/v1/topicos-adocao/{id}   (autenticado)
 */
class TopicoApiController extends ApiController
{
    public function index(): void
    {
        Response::success(TopicoAdocao::allOrdenados());
    }

    public function store(): void
    {
        Auth::requireApiAuth();

        $texto = sanitize((string) $this->request->input('texto', ''));
        $ordem = (int) $this->request->input('ordem', 0);

        if ($texto === '') {
            Response::error('Texto do tópico é obrigatório.', 422);
        }

        $id = TopicoAdocao::create($texto, $ordem);
        Response::success(TopicoAdocao::find($id), 201, 'Tópico criado.');
    }

    public function update(string $id): void
    {
        Auth::requireApiAuth();

        $topico = TopicoAdocao::find((int) $id);
        if (!$topico) {
            Response::error('Tópico não encontrado.', 404);
        }

        $texto = sanitize((string) $this->request->input('texto', $topico['texto']));
        $ordem = (int) $this->request->input('ordem', $topico['ordem']);

        TopicoAdocao::update((int) $id, $texto, $ordem);
        Response::success(TopicoAdocao::find((int) $id), 200, 'Tópico atualizado.');
    }

    public function destroy(string $id): void
    {
        Auth::requireApiAuth();

        if (!TopicoAdocao::find((int) $id)) {
            Response::error('Tópico não encontrado.', 404);
        }

        TopicoAdocao::delete((int) $id);
        Response::success(null, 200, 'Tópico removido.');
    }
}
