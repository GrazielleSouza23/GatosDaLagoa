<?php
/** @var array $configs */
/** @var array|null $admin */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($configs['site_descricao']['valor'] ?? 'Acompanhe as ações da ONG Gatos da Lagoa Taquaral em Campinas. Cuidamos de gatinhos abandonados com amor, responsabilidade e dedicação.'); ?>">
    <title>Admin - <?php echo e($configs['site_titulo']['valor'] ?? SITE_NAME); ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/font.css">
    <link rel="icon" type="image/png" href="/assets/images/logo/logo.png">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <img src="/assets/images/logo/logocomnome.png" alt="Logo ONG Gatos da Lagoa Taquaral">
            </div>
            <nav class="nav-menu" id="navMenu" aria-label="Menu Administrativo">
                <ul class="nav-list">
                    <li><a href="/admin/dashboard" class="nav-link">Dashboard</a></li>
                    <li><a href="/admin/eventos" class="nav-link">Eventos</a></li>
                    <li><a href="/admin/galeria" class="nav-link">Galeria</a></li>
                    <li><a href="/admin/historias" class="nav-link">Histórias</a></li>
                    <li><a href="/admin/configuracoes" class="nav-link">Configurações</a></li>
                    <li><a href="/admin/perfil" class="nav-link">Perfil</a></li>
                    <li><a href="/admin/logout" class="nav-link">Sair</a></li>
                    <li><a href="/" class="nav-link" target="_blank" rel="noopener">Ver Site</a></li>
                </ul>
            </nav>
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <main class="main-content">
