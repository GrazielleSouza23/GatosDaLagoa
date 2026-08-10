<?php
/**
 * Funções auxiliares globais..
 */

function sanitize(string $data): string
{
    return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
}

function sanitizeDeep(array|string $value): array|string
{
    return is_array($value)
        ? array_map('sanitizeDeep', $value)
        : sanitize($value);
}

function validateEmail(string $email): bool|string
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_ARGON2ID);
}

function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(?string $token): bool
{
    return $token !== null
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function uploadImage(array $file, string $prefix = ''): string|false
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return false;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $validMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    if (!isset($validMimeTypes[$mimeType])) {
        return false;
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    if (!getimagesize($file['tmp_name'])) {
        return false;
    }

    [$w, $h] = getimagesize($file['tmp_name']);
    if ($w > 3000 || $h > 3000) {
        return false;
    }

    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0755, true);
    }

    $extension = $validMimeTypes[$mimeType];
    $filename = $prefix . bin2hex(random_bytes(16)) . '.' . $extension;

    return move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename) ? $filename : false;
}

function deleteImage(?string $filename): bool
{
    if (!$filename) {
        return false;
    }
    $path = UPLOAD_PATH . $filename;
    return file_exists($path) ? unlink($path) : false;
}

function systemLog(string $message): void
{
    $logFile = STORAGE_PATH . '/logs/system.log';
    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . "] $message\n", 3, $logFile);
}

function formatDateBR(?string $date): string
{
    if (empty($date)) {
        return '';
    }
    try {
        return (new DateTime($date))->format('d/m/Y');
    } catch (Exception $e) {
        return 'Data inválida';
    }
}

function formatDateTimeBR(?string $datetime): string
{
    if (!$datetime) {
        return '-';
    }
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', preg_replace('/,\d+$/', '', $datetime));
    return $dt ? $dt->format('d/m/Y H:i') : '-';
}

function old(string $key, string $default = ''): string
{
    return htmlspecialchars($_POST[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}


function sendSecurityHeaders(): void
{
    // Evita que o site seja aberto dentro de iframes de outros sites (Clickjacking)
    header("X-Frame-Options: SAMEORIGIN");

    // Impede que o navegador adivinhe o tipo do arquivo (MIME-sniffing)
    header("X-Content-Type-Options: nosniff");

    // Controla o envio de informações de referência (Referrer)
    header("Referrer-Policy: strict-origin-when-cross-origin");

    // Cross-Origin-Opener-Policy (COOP)
    header("Cross-Origin-Opener-Policy: same-origin");

    // Content Security Policy (CSP) básica recomendada
    // (Atenção: Se você carrega scripts, fontes ou imagens de CDNs externas,
    // será necessário ajustar as regras de domínios permitidos aqui)
    // header("Content-Security-Policy: default-src 'self' https: data:;");
}
