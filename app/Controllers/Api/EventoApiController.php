<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;
use App\Models\Evento;

/**
 * GET    /api/v1/eventos            lista eventos ativos
 * GET    /api/v1/eventos/{id}       detalhe de um evento
 * POST   /api/v1/eventos            cria (autenticado)
 * PUT    /api/v1/eventos/{id}       atualiza (autenticado)
 * DELETE /api/v1/eventos/{id}       remove - soft delete (autenticado)
 */
class EventoApiController extends ApiController
{
    public function index(): void
    {
        $futuro = $this->request->query('futuros');
        if ($futuro !== null) {
            Response::success(Evento::futuros());
        }
        Response::success(Evento::ativos());
    }

    public function show(string $id): void
    {
        $evento = Evento::findAtiva((int) $id);
        if (!$evento) {
            Response::error('Evento não encontrado.', 404);
        }
        Response::success($evento);
    }

    public function store(): void
    {
        Auth::requireApiAuth();

        $titulo = trim((string) $this->request->input('titulo', ''));
        $dataEvento = (string) $this->request->input('data_evento', '');

        if ($titulo === '' || $dataEvento === '') {
            Response::error('Título e data do evento são obrigatórios.', 422);
        }

        $data = [
            'titulo' => sanitize($titulo),
            'descricao' => sanitize((string) $this->request->input('descricao', '')),
            'data_evento' => $dataEvento,
            'hora_evento' => $this->request->input('hora_evento'),
            'local_evento' => sanitize((string) $this->request->input('local_evento', '')),
        ];

        $file = $this->request->file('imagem');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imagem = uploadImage($file, 'evento_');
            if (!$imagem) {
                Response::error('Erro no upload da imagem.', 422);
            }
            $data['imagem'] = $imagem;
        }

        $id = Evento::create($data, (int) Auth::id());
        Response::success(Evento::findAtiva($id), 201, 'Evento criado com sucesso.');
    }

    public function update(string $id): void
    {
        Auth::requireApiAuth();

        $evento = Evento::findAtiva((int) $id);
        if (!$evento) {
            Response::error('Evento não encontrado.', 404);
        }

        $data = [
            'titulo' => sanitize((string) $this->request->input('titulo', $evento['titulo'])),
            'descricao' => sanitize((string) $this->request->input('descricao', $evento['descricao'])),
            'data_evento' => (string) $this->request->input('data_evento', $evento['data_evento']),
            'hora_evento' => $this->request->input('hora_evento', $evento['hora_evento']),
            'local_evento' => sanitize((string) $this->request->input('local_evento', $evento['local_evento'])),
        ];

        $file = $this->request->file('imagem');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $imagem = uploadImage($file, 'evento_');
            if (!$imagem) {
                Response::error('Erro no upload da imagem.', 422);
            }
            if ($evento['imagem']) {
                deleteImage($evento['imagem']);
            }
            $data['imagem'] = $imagem;
        }

        Evento::update((int) $id, $data);
        Response::success(Evento::findAtiva((int) $id), 200, 'Evento atualizado com sucesso.');
    }

    public function destroy(string $id): void
    {
        Auth::requireApiAuth();

        $evento = Evento::findAtiva((int) $id);
        if (!$evento) {
            Response::error('Evento não encontrado.', 404);
        }

        if ($evento['imagem']) {
            deleteImage($evento['imagem']);
        }
        Evento::softDelete((int) $id);
        Response::success(null, 200, 'Evento removido com sucesso.');
    }
}
