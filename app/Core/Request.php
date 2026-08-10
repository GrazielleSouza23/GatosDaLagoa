<?php

namespace App\Core;

/**
 * Encapsula a requisição HTTP atual: método, corpo JSON, query string, dados de formulário e arquivos enviados.
 */
class Request
{
    private array $json;

    public function __construct()
    {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);
        $this->json = is_array($decoded) ? $decoded : [];
    }

    public function method(): string
    {
        $override = $this->json['_method'] ?? $_POST['_method'] ?? null;
        if ($override) {
            return strtoupper($override);
        }
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /** Retorna um valor do corpo (JSON) ou do POST tradicional, nessa ordem. */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->json[$key] ?? $_POST[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($_POST, $this->json);
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $header, $m)) {
            return trim($m[1]);
        }
        return null;
    }
}
