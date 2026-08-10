<?php

namespace App\Core;

use App\Models\Configuracao;
use App\Models\RedeSocial;

/**
 * Controller base do site/admin (respostas em HTML via Views).
 * Controllers da API REST usam App\Core\ApiController (ver arquivo próprio).
 */
abstract class Controller
{
    /** Renderiza uma view dentro do layout público (header/footer do site). */
    protected function view(string $view, array $data = []): void
    {
        $data['configs'] = $data['configs'] ?? Configuracao::allAsMap();
        $data['redes'] = $data['redes'] ?? RedeSocial::all();

        extract($data);
        require APP_PATH . '/Views/layouts/site_header.php';
        require APP_PATH . '/Views/site/' . $view . '.php';
        require APP_PATH . '/Views/layouts/site_footer.php';
    }

    /** Renderiza uma view dentro do layout administrativo. */
    protected function adminView(string $view, array $data = []): void
    {
        $data['configs'] = $data['configs'] ?? Configuracao::allAsMap();
        $data['admin'] = $data['admin'] ?? Auth::user();

        extract($data);
        require APP_PATH . '/Views/layouts/admin_header.php';
        require APP_PATH . '/Views/admin/' . $view . '.php';
        require APP_PATH . '/Views/layouts/admin_footer.php';
    }

    /** Renderiza uma view "solta", sem layout (ex.: tela de login). */
    protected function bareView(string $view, array $data = []): void
    {
        $data['configs'] = $data['configs'] ?? Configuracao::allAsMap();
        extract($data);
        require APP_PATH . '/Views/admin/' . $view . '.php';
    }
}
