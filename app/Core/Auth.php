<?php

namespace App\Core;

use App\Models\Administrador;

/**
 * Autenticação do painel administrativo.
 * Usa sessão PHP nativa - reaproveitada tanto pelas rotas do site/admin
 * (formulários tradicionais) quanto pela API REST (fetch com credentials
 * inclusas), evitando duplicar lógica de login.
 */
class Auth
{
    public static function attempt(string $email, string $senha): bool
    {
        $admin = Administrador::findByEmail($email);

        if (!$admin || !verifyPassword($senha, $admin['senha'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_nome'] = $admin['nome'];

        Administrador::touchLastLogin((int) $admin['id']);

        return true;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function id(): ?int
    {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return [
            'id' => $_SESSION['admin_id'],
            'email' => $_SESSION['admin_email'],
            'nome' => $_SESSION['admin_nome'],
        ];
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    /** Usado pelas rotas do painel (redireciona para o login). */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/admin/login');
        }
    }

    /** Usado pela API REST (responde 401 em JSON). */
    public static function requireApiAuth(): void
    {
        if (!self::check()) {
            Response::error('Não autenticado. Faça login em /api/v1/auth/login.', 401);
        }
    }
}
