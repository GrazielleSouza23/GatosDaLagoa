<?php
/**
 * Configurações centrais do sistema - ONG Gatos da Lagoa do Taquaral
 */

// Impede acesso direto ao arquivo
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    http_response_code(403);
    exit('Acesso negado.');
}

/* ============================================================
   Cabeçalhos de Segurança
   ============================================================ */
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

/* ============================================================
   Configurações do Banco de Dados
   ============================================================ */
define('DB_HOST', 'localhost');
define('DB_NAME', 'ong_gatos_taquaral');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');

/* ============================================================
   Configurações Gerais
   ============================================================ */
date_default_timezone_set('America/Sao_Paulo');

$isHttps =
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

define('SITE_URL', $isHttps ? 'https://localhost' : 'http://localhost');
define('SITE_NAME', 'ONG - Gatos da Lagoa Taquaral');
define('ADMIN_EMAIL', 'email@exemplo.com');

/* ============================================================
   Upload de Arquivos
   ============================================================ */
define('UPLOAD_PATH', __DIR__ . '/../assets/images/uploads/');
define('UPLOAD_URL', '/assets/images/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

$allowedImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];

/* ============================================================
   Configurações de Sessão
   ============================================================ */
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', $isHttps ? 1 : 0);
ini_set('session.use_strict_mode', 1);

session_start();
// Regenera em intervalos seguros (evita regenerar a cada refresh)
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 600) {
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

/* ============================================================
   Funções de Banco de Dados
   ============================================================ */

/**
 * Conexão segura com PDO
 */
function getDatabase() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        systemLog("Erro de conexão: " . $e->getMessage());
        http_response_code(500);
        exit("Erro interno no servidor.");
    }
}

/**
 * Execução segura de SQL
 */
function dbQuery($sql, $params = []) {
    $pdo = getDatabase();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/* ============================================================
   Autenticação
   ============================================================ */

function isLoggedIn() {
    return !empty($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function logout() {
    session_unset();
    session_destroy();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    header('Location: /admin/login.php');
    exit;
}

/* ============================================================
   CSRF - Segurança para formulários
   ============================================================ */
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/* ============================================================
   Sanitização e Validação
   ============================================================ */
function sanitize($data) {
    return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
}

function sanitizeDeep($array) {
    return is_array($array)
        ? array_map('sanitizeDeep', $array)
        : sanitize($array);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/* ============================================================
   Upload Seguro de Imagens
   ============================================================ */

function uploadImage($file, $prefix = '') {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return false;

    // MIME real
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $validMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($validMimeTypes[$mimeType])) return false;

    // Verifica tamanho
    if ($file['size'] > MAX_FILE_SIZE) return false;

    // Verifica se é realmente imagem
    if (!getimagesize($file['tmp_name'])) return false;

    // Dimensões máximas
    $maxWidth = 3000;
    $maxHeight = 3000;
    [$w, $h] = getimagesize($file['tmp_name']);
    if ($w > $maxWidth || $h > $maxHeight) return false;

    if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);

    // Nome seguro
    $extension = $validMimeTypes[$mimeType];
    $filename = $prefix . bin2hex(random_bytes(16)) . '.' . $extension;

    return move_uploaded_file($file['tmp_name'], UPLOAD_PATH . $filename) ? $filename : false;
}

function deleteImage($filename) {
    $path = UPLOAD_PATH . $filename;
    return (file_exists($path)) ? unlink($path) : false;
}

/* ============================================================
   Logs
   ============================================================ */
function systemLog($message) {
    $logFile = __DIR__ . '/../logs/system.log';
    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    error_log("[" . date('Y-m-d H:i:s') . "] $message\n", 3, $logFile);
}

/* ============================================================
   Formatação
   ============================================================ */
function formatDateBR($date) {
    if (empty($date)) return '';
    try {
        return (new DateTime($date))->format('d/m/Y');
    } catch (Exception $e) {
        return 'Data inválida';
    }
}

function formatDateTimeBR($datetime) {
    if (!$datetime) return '-';
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', preg_replace('/,\d+$/', '', $datetime));
    return $dt ? $dt->format('d/m/Y H:i') : '-';
}
?>
