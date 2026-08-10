<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\HistoriaSucesso;

/**
 * GET    /api/v1/historias
 * GET    /api/v1/historias/{id}
 * POST   /api/v1/historias        (autenticado)
 * PUT    /api/v1/historias/{id}   (autenticado)
 * DELETE /api/v1/historias/{id}   (autenticado)
 */
class HistoriaApiController extends ApiController
{
    public function index(): void
    {
        Response::success(HistoriaSucesso::ativas());
    }

    public function show(string $id): void
    {
        $historia = HistoriaSucesso::findAtiva((int) $id);
        if (!$historia) {
            Response::error('História não encontrada.', 404);
        }
        Response::success($historia);
    }

    public function store(): void
    {
        Auth::requireApiAuth();

        $nomeGato = trim((string) $this->request->input('nome_gato', ''));
        if ($nomeGato === '') {
            Response::error('Nome do gato é obrigatório.', 422);
        }

        $data = [
            'nome_gato' => sanitize($nomeGato),
            'idade' => sanitize((string) $this->request->input('idade', '')),
            'descricao' => sanitize((string) $this->request->input('descricao', '')),
            'historia' => sanitize((string) $this->request->input('historia', '')),
            'data_adocao' => $this->request->input('data_adocao'),
            'nome_adotante' => sanitize((string) $this->request->input('nome_adotante', '')),
        ];

        $file = $this->request->file('imagem');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imagem = uploadImage($file, 'historia_');
            if (!$imagem) {
                Response::error('Erro no upload da imagem.', 422);
            }
            $data['imagem'] = $imagem;
        }

        $id = HistoriaSucesso::create($data, (int) Auth::id());
        Response::success(HistoriaSucesso::findAtiva($id), 201, 'História criada com sucesso.');
    }

    public function update(string $id): void
    {
        Auth::requireApiAuth();

        $historia = HistoriaSucesso::findAtiva((int) $id);
        if (!$historia) {
            Response::error('História não encontrada.', 404);
        }

        $data = [
            'nome_gato' => sanitize((string) $this->request->input('nome_gato', $historia['nome_gato'])),
            'idade' => sanitize((string) $this->request->input('idade', $historia['idade'])),
            'descricao' => sanitize((string) $this->request->input('descricao', $historia['descricao'])),
            'historia' => sanitize((string) $this->request->input('historia', $historia['historia'])),
            'data_adocao' => $this->request->input('data_adocao', $historia['data_adocao']),
            'nome_adotante' => sanitize((string) $this->request->input('nome_adotante', $historia['nome_adotante'])),
        ];

        $file = $this->request->file('imagem');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imagem = uploadImage($file, 'historia_');
            if (!$imagem) {
                Response::error('Erro no upload da imagem.', 422);
            }
            if ($historia['imagem']) {
                deleteImage($historia['imagem']);
            }
            $data['imagem'] = $imagem;
        }

        HistoriaSucesso::update((int) $id, $data);
        Response::success(HistoriaSucesso::findAtiva((int) $id), 200, 'História atualizada com sucesso.');
    }

    public function destroy(string $id): void
    {
        Auth::requireApiAuth();

        $historia = HistoriaSucesso::findAtiva((int) $id);
        if (!$historia) {
            Response::error('História não encontrada.', 404);
        }

        if ($historia['imagem']) {
            deleteImage($historia['imagem']);
        }
        HistoriaSucesso::softDelete((int) $id);
        Response::success(null, 200, 'História removida com sucesso.');
    }
}
