<?php
/** @var array $configs */
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e($configs['site_descricao']['valor'] ?? 'Somos um grupo de cidadãos unidos pelo bem estar e cuidado dos gatos que vivem no Parque Portugal, em Campinas, SP. Juntos, alimentamos, castramos e promovemos adoções responsáveis.'); ?>">
    <title><?php echo e($configs['site_titulo']['valor'] ?? SITE_NAME); ?></title>
    <link rel="stylesheet" href="/assets/css/font.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/assets/images/logo/logo.png">
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="/">
                    <img src="/assets/images/logo/logocomnome.png" alt="Logo ONG Gatos da Lagoa Taquaral">
                </a>
            </div>
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li><a href="/" class="nav-link <?php echo $activePage === 'home' ? 'active' : ''; ?>"><img src="/assets/images/icones/home.png" alt="ícone"></a></li>
                    <li><a href="/quem-somos" class="nav-link <?php echo $activePage === 'quem-somos' ? 'active' : ''; ?>">Quem Somos</a></li>
                    <li><a href="/#adocao" class="nav-link">Adoção</a></li>
                    <li><a href="/eventos" class="nav-link <?php echo $activePage === 'eventos' ? 'active' : ''; ?>">Eventos</a></li>
                    <li><a href="/voluntariado" class="nav-link <?php echo $activePage === 'voluntariado' ? 'active' : ''; ?>">Voluntariado</a></li>
                    <li><a href="/doacoes" class="nav-link <?php echo $activePage === 'doacoes' ? 'active' : ''; ?>">Doações</a></li>
                    <li><a href="/galeria" class="nav-link <?php echo $activePage === 'galeria' ? 'active' : ''; ?>">Galeria</a></li>
                    <li><a href="/contato" class="nav-link <?php echo $activePage === 'contato' ? 'active' : ''; ?>">Contato</a></li>
                    <li><a href="/admin/login" class="nav-link"><img src="/assets/images/icones/user.png" alt="ícone"></a></li>
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