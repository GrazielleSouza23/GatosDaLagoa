<?php

namespace App\Core;

/**
 * Router simples baseado em expressões regulares.
 * Usado tanto pelo front controller do site/admin (public/index.php)
 * quanto pelo front controller da API REST (public/api/index.php).
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, array $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, array $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    private function add(string $method, string $path, array $handler): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', trim($path, '/'));
        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    /**
     * Resolve a URI atual contra as rotas cadastradas e executa o Controller.
     * $basePath permite montar o router a partir de um sub-diretório
     * (ex.: "/api/v1") sem repetir o prefixo em cada rota.
     */
    public function dispatch(string $method, string $uri, string $basePath = ''): void
    {
        // Permite spoofing de método (PUT/DELETE) via campo _method,
        // necessário para uploads multipart (PHP só popula $_FILES em POST).
        if ($method === 'POST' && !empty($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';

        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], trim($uri, '/'), $matches)) {
                array_shift($matches);
                [$class, $action] = $route['handler'];
                $controller = new $class();
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        $this->notFound();
    }


    private function notFound(): void {
    if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
        Response::error('Rota não encontrada.', 404);
    }

    http_response_code(404);

    echo '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>404 - Página não encontrada</title>

        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="icon" type="image/png" href="/assets/images/logo/logo.png">
    </head>


    <body>

        <main class="main-content error-page">
            <section class="error-section">
                <div class="container">
                    <div class="error-content">
                        <h1 class="error-code">404</h1>

                        <h2 class="error-title">
                            Página Não Encontrada
                        </h2>

                        <p class="error-message">
                            Ops! Parece que a página que você está procurando não existe ou foi movida.
                        </p>

                        <p class="error-suggestion">
                            Não se preocupe, você pode voltar para a página inicial.
                        </p>

                        <a href="/" class="btn btn-primary">
                            Voltar ao Início
                        </a>
                    </div>
                </div>
            </section>
        </main>

    </body>
    </html>';

    exit;
}



}
