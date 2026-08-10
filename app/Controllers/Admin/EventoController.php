<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Evento;

class EventoController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$message, $error] = $this->handlePost();
        }

        $eventoEdit = null;
        if (isset($_GET['edit'])) {
            $eventoEdit = Evento::findAtiva((int) $_GET['edit']) ?: null;
        }

        $this->adminView('eventos', [
            'eventos' => Evento::ativos(),
            'eventoEdit' => $eventoEdit,
            'message' => $message,
            'error' => $error,
        ]);
    }

    /** @return array{0:string,1:string} [message, error] */
    private function handlePost(): array
    {
        $message = '';
        $error = '';
        $action = $_POST['action'] ?? '';

        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            return ['', 'Sessão expirada, tente novamente.'];
        }

        if ($action === 'remove_image') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $evento = Evento::findAtiva($id);
                if ($evento && $evento['imagem']) {
                    deleteImage($evento['imagem']);
                    Evento::removeImagem($id);
                }
                $_SESSION['message'] = 'Imagem removida com sucesso!';
                redirect('/admin/eventos?edit=' . $id);
            }
        }

        if ($action === 'add' || $action === 'edit') {
            $data = [
                'titulo' => sanitize($_POST['titulo'] ?? ''),
                'descricao' => sanitize($_POST['descricao'] ?? ''),
                'data_evento' => $_POST['data_evento'] ?? '',
                'hora_evento' => $_POST['hora_evento'] ?? '',
                'local_evento' => sanitize($_POST['local_evento'] ?? ''),
            ];
            $id = (int) ($_POST['id'] ?? 0);

            if (empty($data['titulo']) || empty($data['data_evento'])) {
                return ['', 'Título e data do evento são obrigatórios.'];
            }

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'evento_');
                if (!$imagem) {
                    return ['', 'Erro no upload da imagem. Verifique o formato e tamanho.'];
                }
                $data['imagem'] = $imagem;
            }

            if ($action === 'add') {
                Evento::create($data, (int) Auth::id());
                $message = 'Evento adicionado com sucesso!';
            } else {
                if (!empty($data['imagem'])) {
                    $old = Evento::findAtiva($id);
                    if ($old && $old['imagem']) {
                        deleteImage($old['imagem']);
                    }
                }
                Evento::update($id, $data);
                $_SESSION['message'] = 'Evento atualizado com sucesso!';
                redirect('/admin/eventos');
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $evento = Evento::findAtiva($id);
                if ($evento && $evento['imagem']) {
                    deleteImage($evento['imagem']);
                }
                Evento::softDelete($id);
                $message = 'Evento removido com sucesso!';
            }
        }

        return [$message, $error];
    }
}
