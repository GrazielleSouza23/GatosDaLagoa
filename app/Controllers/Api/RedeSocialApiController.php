<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\RedeSocial;

/**
 * GET    /api/v1/redes-sociais
 * POST   /api/v1/redes-sociais        (autenticado)
 * PUT    /api/v1/redes-sociais/{id}   (autenticado)
 * DELETE /api/v1/redes-sociais/{id}   (autenticado)
 */
class RedeSocialApiController extends ApiController
{
    public function index(): void
    {
        Response::success(RedeSocial::all());
    }

    public function store(): void
    {
        Auth::requireApiAuth();

        $chave = sanitize((string) $this->request->input('chave', ''));
        $icone = sanitize((string) $this->request->input('icone', ''));
        $link = sanitize((string) $this->request->input('link', ''));

        if ($chave === '' || $icone === '' || $link === '') {
            Response::error('Informe chave, icone e link.', 422);
        }

        $id = RedeSocial::create(['chave' => $chave, 'icone' => $icone, 'link' => $link]);
        Response::success(RedeSocial::find($id), 201, 'Rede social criada.');
    }

    public function update(string $id): void
    {
        Auth::requireApiAuth();

        $rede = RedeSocial::find((int) $id);
        if (!$rede) {
            Response::error('Rede social não encontrada.', 404);
        }

        $data = [
            'chave' => sanitize((string) $this->request->input('chave', $rede['chave'])),
            'icone' => sanitize((string) $this->request->input('icone', $rede['icone'])),
            'link' => sanitize((string) $this->request->input('link', $rede['link'])),
        ];

        RedeSocial::update((int) $id, $data);
        Response::success(RedeSocial::find((int) $id), 200, 'Rede social atualizada.');
    }

    public function destroy(string $id): void
    {
        Auth::requireApiAuth();

        if (!RedeSocial::find((int) $id)) {
            Response::error('Rede social não encontrada.', 404);
        }

        RedeSocial::delete((int) $id);
        Response::success(null, 200, 'Rede social removida.');
    }
}
