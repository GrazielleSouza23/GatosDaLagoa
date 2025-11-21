<?php
require_once 'config.php';

// Iniciar sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirecionar se não estiver logado
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$db = getDatabase();

$stmt = $db->query("SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave");

$configs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $chave = $row['chave'];
    $valor = $row["valor"];
    $descricao = $row["descricao"];
    $configs[$chave] = [
        'valor' => $valor ?? '',
        'descricao' => $descricao ?? '',
        'atualizacao' => $row['data_atualizacao']
    ];
}

$stmt = $db->query("SELECT id, chave, icone, link FROM redes_sociais");

$redes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($configs["site_descricao"]["valor"] ?? ''); ?>">
    <title>Admin - <?php echo htmlspecialchars($configs["site_titulo"]["valor"] ?? ''); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/font.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/logo.png">
</head>
<body>
    <!-- Header com navegação para Admin -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <img src="../assets/images/logo/logocomnome.png" alt="Logo ONG Gatos da Lagoa Taquaral">
            </div>
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="eventos.php" class="nav-link">Eventos</a></li>
                    <li><a href="galeria.php" class="nav-link">Galeria</a></li>
                    <li><a href="historias.php" class="nav-link">Histórias</a></li>
                    <li><a href="configuracoes.php" class="nav-link">Configurações</a></li>
                    <li><a href="perfil.php" class="nav-link">Perfil</a></li>
                    <li><a href="logout.php" class="nav-link">Sair</a></li>
                    <li><a href="../index.php" class="nav-link" target="_blank">Ver Site</a></li>
                </ul>
            </nav>
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>