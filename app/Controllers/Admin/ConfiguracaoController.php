<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Configuracao;
use App\Models\RedeSocial;
use App\Models\TopicoAdocao;

class ConfiguracaoController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $message = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$message, $error] = $this->handlePost();
        }

        $this->adminView('configuracoes', [
            'configsList' => Configuracao::allList(),
            'redes' => RedeSocial::all(),
            'topicos' => TopicoAdocao::allOrdenados(),
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

        if ($action === 'update_configs') {
            $pares = $_POST['config'] ?? [];
            $sanitizado = [];
            foreach ($pares as $chave => $valor) {
                $sanitizado[sanitize((string) $chave)] = sanitize((string) $valor);
            }
            Configuracao::setMany($sanitizado);
            return ['Configurações atualizadas com sucesso!', ''];
        }

        if ($action === 'add_rede') {
            $chave = sanitize($_POST['chave'] ?? '');
            $link  = sanitize($_POST['link'] ?? '');

            if (empty($chave) || empty($link)) {
                return ['', 'Preencha todos os campos da rede social.'];
            }

            if (
                !isset($_FILES['icone']) ||
                $_FILES['icone']['error'] !== UPLOAD_ERR_OK
            ) {
                return ['', 'Selecione um ícone.'];
            }

            $ext = strtolower(pathinfo($_FILES['icone']['name'], PATHINFO_EXTENSION));

            $permitidos = ['png', 'jpg', 'jpeg', 'webp', 'svg'];

            if (!in_array($ext, $permitidos)) {
                return ['', 'Formato de imagem inválido.'];
            }

            $nomeArquivo = uniqid('icone_') . '.' . $ext;

            $destino = __DIR__ . '/../../../public/assets/images/icones/' . $nomeArquivo;

            if (!move_uploaded_file($_FILES['icone']['tmp_name'], $destino)) {
                return ['', 'Erro ao enviar o ícone.'];
            }

            RedeSocial::create([
                'chave' => $chave,
                'icone' => $nomeArquivo,
                'link'  => $link
            ]);

            return ['Rede social adicionada!', ''];
        }


        if ($action === 'delete_rede') {
            $id = (int) ($_POST['id'] ?? 0);

            $rede = RedeSocial::find($id);

            if ($rede && !empty($rede['icone'])) {
                $arquivo = __DIR__ . '/../../../public/assets/images/icones/' . $rede['icone'];

                if (file_exists($arquivo)) {
                    unlink($arquivo);
                }
            }

            RedeSocial::delete($id);

            return ['Rede social removida!', ''];
        }


        if ($action === 'add_topico') {
            $texto = sanitize($_POST['texto'] ?? '');
            $ordem = (int) ($_POST['ordem'] ?? 0);
            if (empty($texto)) {
                return ['', 'Texto do tópico é obrigatório.'];
            }
            TopicoAdocao::create($texto, $ordem);
            return ['Tópico adicionado!', ''];
        }

        if ($action === 'delete_topico') {
            TopicoAdocao::delete((int) ($_POST['id'] ?? 0));
            return ['Tópico removido!', ''];
        }

        return ['', ''];
    }
}
