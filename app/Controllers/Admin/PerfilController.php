<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Administrador;

class PerfilController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $message = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$message, $error] = $this->handlePost();
        }

        $admin = Administrador::findById((int) Auth::id());

        $stats = [
            'eventos' => Database::query('SELECT COUNT(*) AS total FROM eventos WHERE admin_id = ? AND ativo = 1', [Auth::id()])->fetch()['total'],
            'historias' => Database::query('SELECT COUNT(*) AS total FROM historias_sucesso WHERE admin_id = ? AND ativo = 1', [Auth::id()])->fetch()['total'],
            'galeria' => Database::query('SELECT COUNT(*) AS total FROM galeria WHERE admin_id = ? AND ativo = 1', [Auth::id()])->fetch()['total'],
        ];

        $this->adminView('perfil', [
            'admin' => $admin,
            'stats' => $stats,
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

        if ($action === 'update_profile') {
            $nome = sanitize($_POST['nome'] ?? '');
            $email = sanitize($_POST['email'] ?? '');

            if (empty($nome) || empty($email)) {
                return ['', 'Nome e email são obrigatórios.'];
            }
            if (!validateEmail($email)) {
                return ['', 'Email inválido.'];
            }

            $existe = Database::query('SELECT id FROM administradores WHERE email = ? AND id != ?', [$email, Auth::id()])->fetch();
            if ($existe) {
                return ['', 'Este email já está sendo usado por outro administrador.'];
            }

            Administrador::updatePerfil((int) Auth::id(), $nome, $email);
            $_SESSION['admin_nome'] = $nome;
            $_SESSION['admin_email'] = $email;
            return ['Perfil atualizado com sucesso!', ''];
        }

        if ($action === 'change_password') {
            $senhaAtual = $_POST['senha_atual'] ?? '';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';

            if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
                return ['', 'Todos os campos de senha são obrigatórios.'];
            }
            if ($novaSenha !== $confirmarSenha) {
                return ['', 'A nova senha e a confirmação não coincidem.'];
            }
            if (strlen($novaSenha) < 6) {
                return ['', 'A nova senha deve ter pelo menos 6 caracteres.'];
            }

            $hashAtual = Administrador::senhaAtualHash((int) Auth::id());
            if (!$hashAtual || !verifyPassword($senhaAtual, $hashAtual)) {
                return ['', 'Senha atual incorreta.'];
            }

            Administrador::updateSenha((int) Auth::id(), hashPassword($novaSenha));
            return ['Senha alterada com sucesso!', ''];
        }

        return ['', ''];
    }
}