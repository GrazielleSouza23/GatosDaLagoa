<?php
/**
 * Configurações centrais do sistema - ONG Gatos da Lagoa do Taquaral
 */

require_once __DIR__ . '/env.php';
/* ============================================================
   Banco de Dados
   ============================================================ */
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? '');
define('DB_USER', $_ENV['DB_USER'] ?? '');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

/* ============================================================
   Caminhos do projeto
   ============================================================ */
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');

/* ============================================================
   Geral
   ============================================================ */
date_default_timezone_set('America/Sao_Paulo');

$isHttps =
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

define('SITE_URL', ($isHttps ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('SITE_NAME', 'ONG - Gatos da Lagoa Taquaral');
define('ADMIN_EMAIL', 'gatosdalagoacampinas@gmail.com');

/* ============================================================
   Upload de Arquivos
   ============================================================ */
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/images/uploads/');
define('UPLOAD_URL', '/assets/images/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

/* ============================================================
   Sessão (usada tanto pelo site/admin quanto pela API)
   ============================================================ */
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', $isHttps ? 1 : 0);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 600) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

/* ============================================================
   Cabeçalhos de Segurança
   ============================================================ */
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Cross-Origin-Opener-Policy: same-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// Content Security Policy (CSP) adaptada para permitir o site e o Google Maps
header("Content-Security-Policy: default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'; frame-src 'self' https://www.google.com https://maps.google.com;");

if ($isHttps) {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
}