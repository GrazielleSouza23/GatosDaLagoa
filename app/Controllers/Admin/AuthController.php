<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/admin/dashboard');
        }
        $this->bareView('login', ['error' => '']);
    }

    public function login(): void
    {
        if (Auth::check()) {
            redirect('/admin/dashboard');
        }

        $email = sanitize($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $error = '';

        if (empty($email) || empty($senha)) {
            $error = 'Por favor, preencha todos os campos.';
        } elseif (!validateEmail($email)) {
            $error = 'Email inválido.';
        } elseif (!Auth::attempt($email, $senha)) {
            $error = 'Email ou senha incorretos.';
        } else {
            redirect('/admin/dashboard');
        }

        $this->bareView('login', ['error' => $error]);
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/admin/login');
    }
}
