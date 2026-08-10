<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Galeria;

class GaleriaController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $message = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$message, $error] = $this->handlePost();
        }

        $imagemEdit = null;
        if (isset($_GET['edit'])) {
            $imagemEdit = Galeria::findAtiva((int) $_GET['edit']) ?: null;
        }

        $this->adminView('galeria', [
            'galeria' => Galeria::ativas(),
            'imagemEdit' => $imagemEdit,
            'message' => $message,
            'error' => $error,
        ]);
    }

    /** @return array{0:string,1:string} */
    private function handlePost(): array
    {
        $action = $_POST['action'] ?? '';

        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            return ['', 'Sessão expirada, tente novamente.'];
        }

        if ($action === 'add' || $action === 'edit') {
            $data = [
                'titulo' => sanitize($_POST['titulo'] ?? ''),
                'descricao' => sanitize($_POST['descricao'] ?? ''),
                'categoria' => sanitize($_POST['categoria'] ?? ''),
            ];
            $id = (int) ($_POST['id'] ?? 0);

            if (empty($data['titulo'])) {
                return ['', 'Título é obrigatório.'];
            }

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'galeria_');
                if (!$imagem) {
                    return ['', 'Erro no upload da imagem. Verifique o formato e tamanho.'];
                }
                $data['imagem'] = $imagem;
            }

            if ($action === 'add') {
                if (empty($data['imagem'])) {
                    return ['', 'Imagem é obrigatória para adicionar à galeria.'];
                }
                Galeria::create($data, (int) Auth::id());
                return ['Imagem adicionada à galeria!', ''];
            }

            if (!empty($data['imagem'])) {
                $old = Galeria::findAtiva($id);
                if ($old && $old['imagem']) {
                    deleteImage($old['imagem']);
                }
            }
            Galeria::update($id, $data);
            return ['Imagem atualizada!', ''];
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $item = Galeria::findAtiva($id);
                if ($item && $item['imagem']) {
                    deleteImage($item['imagem']);
                }
                Galeria::softDelete($id);
                return ['Imagem removida da galeria!', ''];
            }
        }

        return ['', ''];
    }
}
