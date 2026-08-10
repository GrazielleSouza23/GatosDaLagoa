<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\HistoriaSucesso;

class HistoriaController extends Controller
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

        $historiaEdit = null;
        if (isset($_GET['edit'])) {
            $historiaEdit = HistoriaSucesso::findAtiva((int) $_GET['edit']) ?: null;
        }

        $this->adminView('historias', [
            'historias' => HistoriaSucesso::ativas(),
            'historiaEdit' => $historiaEdit,
            'message' => $message,
            'error' => $error,
        ]);
    }

    /** @return array{0:string,1:string} */
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
                $historia = HistoriaSucesso::findAtiva($id);
                if ($historia && $historia['imagem']) {
                    deleteImage($historia['imagem']);
                    HistoriaSucesso::removeImagem($id);
                }
                $_SESSION['message'] = 'Imagem removida com sucesso!';
                redirect('/admin/historias?edit=' . $id);
            }
        }

        if ($action === 'add' || $action === 'edit') {
            $data = [
                'nome_gato' => sanitize($_POST['nome_gato'] ?? ''),
                'idade' => sanitize($_POST['idade'] ?? ''),
                'descricao' => sanitize($_POST['descricao'] ?? ''),
                'historia' => sanitize($_POST['historia'] ?? ''),
                'data_adocao' => $_POST['data_adocao'] ?? '',
                'nome_adotante' => sanitize($_POST['nome_adotante'] ?? ''),
            ];
            $id = (int) ($_POST['id'] ?? 0);

            if (empty($data['nome_gato'])) {
                return ['', 'Nome do gato é obrigatório.'];
            }

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'historia_');
                if (!$imagem) {
                    return ['', 'Erro no upload da imagem. Verifique o formato e tamanho.'];
                }
                $data['imagem'] = $imagem;
            }

            if ($action === 'add') {
                HistoriaSucesso::create($data, (int) Auth::id());
                $message = 'História adicionada com sucesso!';
            } else {
                if (!empty($data['imagem'])) {
                    $old = HistoriaSucesso::findAtiva($id);
                    if ($old && $old['imagem']) {
                        deleteImage($old['imagem']);
                    }
                }
                HistoriaSucesso::update($id, $data);
                $_SESSION['message'] = 'História atualizada com sucesso!';
                redirect('/admin/historias');
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $historia = HistoriaSucesso::findAtiva($id);
                if ($historia && $historia['imagem']) {
                    deleteImage($historia['imagem']);
                }
                HistoriaSucesso::softDelete($id);
                $message = 'História removida com sucesso!';
            }
        }

        return [$message, $error];
    }
}
