<?php

namespace App\Controllers\Api;

use App\Core\ApiController;
use App\Core\Auth;
use App\Core\Response;

/**
 * POST   /api/v1/auth/login
 * POST   /api/v1/auth/logout
 * GET    /api/v1/auth/me
 */
class AuthApiController extends ApiController
{
    public function login(): void
    {
        $email = (string) $this->request->input('email', '');
        $senha = (string) $this->request->input('senha', '');

        if (empty($email) || empty($senha)) {
            Response::error('Informe email e senha.', 422);
        }
        if (!validateEmail($email)) {
            Response::error('Email inválido.', 422);
        }
        if (!Auth::attempt($email, $senha)) {
            Response::error('Email ou senha incorretos.', 401);
        }

        Response::success(Auth::user(), 200, 'Login realizado com sucesso.');
    }

    public function logout(): void
    {
        Auth::logout();
        Response::success(null, 200, 'Sessão encerrada.');
    }

    public function me(): void
    {
        Auth::requireApiAuth();
        Response::success(Auth::user());
    }
}
