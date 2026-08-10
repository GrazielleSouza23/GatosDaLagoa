<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\Galeria;

/**
 * GET    /api/v1/galeria
 * GET    /api/v1/galeria/{id}
 * POST   /api/v1/galeria        (autenticado, imagem obrigatória)
 * PUT    /api/v1/galeria/{id}   (autenticado)
 * DELETE /api/v1/galeria/{id}   (autenticado)
 */
class GaleriaApiController extends ApiController
{
    public function index(): void
    {
        Response::success(Galeria::ativas());
    }

    public function show(string $id): void
    {
        $item = Galeria::findAtiva((int) $id);
        if (!$item) {
            Response::error('Imagem não encontrada.', 404);
        }
        Response::success($item);
    }

    public function store(): void
    {
        Auth::requireApiAuth();

        $titulo = trim((string) $this->request->input('titulo', ''));
        if ($titulo === '') {
            Response::error('Título é obrigatório.', 422);
        }

        $file = $this->request->file('imagem');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            Response::error('Imagem é obrigatória para adicionar à galeria.', 422);
        }

        $imagem = uploadImage($file, 'galeria_');
        if (!$imagem) {
            Response::error('Erro no upload da imagem.', 422);
        }

        $data = [
            'titulo' => sanitize($titulo),
            'descricao' => sanitize((string) $this->request->input('descricao', '')),
            'categoria' => sanitize((string) $this->request->input('categoria', '')),
            'imagem' => $imagem,
        ];

        $id = Galeria::create($data, (int) Auth::id());
        Response::success(Galeria::findAtiva($id), 201, 'Imagem adicionada à galeria.');
    }

    public function update(string $id): void
    {
        Auth::requireApiAuth();

        $item = Galeria::findAtiva((int) $id);
        if (!$item) {
            Response::error('Imagem não encontrada.', 404);
        }

        $data = [
            'titulo' => sanitize((string) $this->request->input('titulo', $item['titulo'])),
            'descricao' => sanitize((string) $this->request->input('descricao', $item['descricao'])),
            'categoria' => sanitize((string) $this->request->input('categoria', $item['categoria'])),
        ];

        $file = $this->request->file('imagem');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imagem = uploadImage($file, 'galeria_');
            if (!$imagem) {
                Response::error('Erro no upload da imagem.', 422);
            }
            if ($item['imagem']) {
                deleteImage($item['imagem']);
            }
            $data['imagem'] = $imagem;
        }

        Galeria::update((int) $id, $data);
        Response::success(Galeria::findAtiva((int) $id), 200, 'Imagem atualizada com sucesso.');
    }

    public function destroy(string $id): void
    {
        Auth::requireApiAuth();

        $item = Galeria::findAtiva((int) $id);
        if (!$item) {
            Response::error('Imagem não encontrada.', 404);
        }

        if ($item['imagem']) {
            deleteImage($item['imagem']);
        }
        Galeria::softDelete((int) $id);
        Response::success(null, 200, 'Imagem removida com sucesso.');
    }
}
