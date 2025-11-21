<?php
require_once 'includes/config.php';

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

$topicos = $db->query("SELECT texto FROM topicos_adocao ORDER BY ordem ASC")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT id, nome_gato, SUBSTRING(descricao, 1, 4000) AS descricao,
    SUBSTRING(historia, 1, 4000) AS historia,
    imagem, nome_adotante, idade, data_adocao
FROM historias_sucesso
WHERE ativo = 1
ORDER BY data_criacao DESC LIMIT 3");
$stmt->execute();
$historias = $stmt->fetchAll();

foreach ($historias as $i => $historia) {
    foreach ($historia as $key => $value) {
        $historias[$i][$key] = $value;
    }
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
    <title><?php echo htmlspecialchars($configs['site_titulo']['valor'] ?? ''); ?></title>
    <link rel="stylesheet" href="assets/css/font.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/images/logo/logo.png">
</head>

<body>
    <!-- Header com navegação -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/logo/logocomnome.png" alt="Logo ONG Gatos da Lagoa Taquaral">
                </a>
            </div>
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link active"><img src="assets/images/icones/home.png" alt="ícone"></a></li>
                    <li><a href="pages/QuemSomos.php" class="nav-link">Quem Somos</a></li>
                    <li><a href="#adocao" class="nav-link">Adoção</a></li>
                    <li><a href="pages/Eventos.php" class="nav-link">Eventos</a></li>
                    <li><a href="pages/Voluntariado.php" class="nav-link">Voluntariado</a></li>
                    <li><a href="pages/Doacoes.php" class="nav-link">Doações</a></li>
                    <li><a href="pages/Galeria.php" class="nav-link">Galeria</a></li>
                    <li><a href="pages/Contato.php" class="nav-link">Contato</a></li>
                    <li><a href="admin/login.php" class="nav-link"><img src="assets/images/icones/user.png" alt="ícone"></a></li>
                </ul>
            </nav>
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-wave-top">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,80 350,0 600,50 C850,100 1050,20 1200,80 L1200,0 L0,0 Z" fill="#65C5B2"></path>
            </svg>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Cuidando dos Gatos da <span class="highlight">Lagoa Taquaral</span></h1>
                <p class="hero-description"><?php echo htmlspecialchars($configs['site_descricao']['valor'] ?? ''); ?></p>
                <div class="hero-buttons">
                    <a href="#adocao" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        Adotar um Gatinho
                    </a>
                    <a href="pages/Doacoes.php" class="btn btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                        Fazer Doação
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-circle">
                    <img src="assets/images/uploads/<?php echo htmlspecialchars($configs['imagem_inicial']['valor'] ?? 'Gato_Inicial.png'); ?>" alt="Gato resgatado pela ONG">
                </div>
                <div class="floating-shape shape-1"></div>
                <div class="floating-shape shape-2"></div>
                <div class="floating-shape shape-3"></div>
            </div>
        </div>
        <div class="hero-wave-bottom">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
            </svg>
        </div>
    </section>

    <!-- Principais Atividades -->
    <section class="activities">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo htmlspecialchars($configs['titulo_atividades']['valor'] ?? ''); ?></p></h2>
                <p class="section-subtitle"><?php echo htmlspecialchars($configs['descricao_atividades']['valor'] ?? ''); ?></p>
            </div>
            <div class="activities-grid">
                <div class="activity-card">
                    <img src="assets/images/icones/icons-cat-bowl.png" style="width: 75px; height: 75px;" alt="Ícone de tigela de ração"><br>
                    <h3 class="activity-title"><?php echo htmlspecialchars($configs['titulo_alimentadores_home']['valor'] ?? ''); ?></h3>
                    <p class="activity-description"><?php echo htmlspecialchars($configs['descricao_alimentadores_home']['valor'] ?? ''); ?></p></p>
                </div>
                <div class="activity-card">
                    <img src="assets/images/icones/animal.png" style="width: 75px; height: 75px;" alt="Ícone de castração"><br>
                    <h3 class="activity-title"><?php echo htmlspecialchars($configs['titulo_castracao']['valor'] ?? ''); ?></p></h3>
                    <p class="activity-description"><?php echo htmlspecialchars($configs['descricao_castracao']['valor'] ?? ''); ?></p></p>
                </div>
                <div class="activity-card">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="75px" height="75px" viewBox="0,0,256,256">
                        <defs>
                            <linearGradient x1="6" y1="41" x2="42" y2="41" gradientUnits="userSpaceOnUse" id="color-1_wFfu6zXx15Yk_gr1">
                                <stop offset="0" stop-color="#6ccb10"></stop>
                                <stop offset="1" stop-color="#6ccb10"></stop>
                            </linearGradient>
                            <linearGradient x1="14.095" y1="10.338" x2="31.385" y2="43.787" gradientUnits="userSpaceOnUse" id="color-2_wFfu6zXx15Yk_gr2">
                                <stop offset="0" stop-color="#65c5b2"></stop>
                                <stop offset="0.495" stop-color="#65c5b2"></stop>
                                <stop offset="0.946" stop-color="#65c5b2"></stop>
                                <stop offset="1" stop-color="#65c5b2"></stop>
                            </linearGradient>
                            <linearGradient x1="24" y1="1.684" x2="24" y2="23.696" gradientUnits="userSpaceOnUse" id="color-3_wFfu6zXx15Yk_gr3">
                                <stop offset="0" stop-color="#6ccb10"></stop><stop offset="1" stop-color="#6ccb10"></stop>
                            </linearGradient>
                            <linearGradient x1="28.05" y1="25.05" x2="35.614" y2="32.614" gradientUnits="userSpaceOnUse" id="color-4_wFfu6zXx15Yk_gr4">
                                <stop offset="0" stop-color="#a8cf45"></stop><stop offset="1" stop-color="#6ccb10"></stop>
                            </linearGradient></defs><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.33333,5.33333)"><path d="M42,39h-36v2c0,1.105 0.895,2 2,2h32c1.105,0 2,-0.895 2,-2z" fill="url(#color-1_wFfu6zXx15Yk_gr1)"></path><path d="M42,39h-36v-19l18,-17l18,17z" fill="url(#color-2_wFfu6zXx15Yk_gr2)"></path><path d="M13,25h10c0.552,0 1,0.448 1,1v17h-12v-17c0,-0.552 0.448,-1 1,-1z" fill="#a8cf45"></path>
                        <path d="M24,4c-0.474,0 -0.948,0.168 -1.326,0.503l-5.359,4.811l-11.315,10.686v5.39l18,-15.962l18,15.962v-5.39l-11.315,-10.686l-5.359,-4.811c-0.378,-0.335 -0.852,-0.503 -1.326,-0.503z" fill="#000000" opacity="0.05"></path><path d="M24,3c-0.474,0 -0.948,0.167 -1.326,0.5l-5.359,4.784l-11.315,10.625v5.359l18,-15.871l18,15.871v-5.359l-11.315,-10.625l-5.359,-4.784c-0.378,-0.333 -0.852,-0.5 -1.326,-0.5z" fill="#000000" opacity="0.07"></path><path d="M44.495,19.507l-19.169,-17.004c-0.378,-0.335 -0.852,-0.503 -1.326,-0.503c-0.474,0 -0.948,0.168 -1.326,0.503l-19.169,17.004c-0.42,0.374 -0.449,1.02 -0.064,1.43l1.636,1.745c0.369,0.394 0.984,0.424 1.39,0.067l17.533,-15.321l17.533,15.322c0.405,0.356 1.021,0.327 1.39,-0.067l1.636,-1.745c0.385,-0.411 0.356,-1.057 -0.064,-1.431z" fill="url(#color-3_wFfu6zXx15Yk_gr3)"></path><path d="M29,25h6c0.552,0 1,0.448 1,1v6c0,0.552 -0.448,1 -1,1h-6c-0.552,0 -1,-0.448 -1,-1v-6c0,-0.552 0.448,-1 1,-1z" fill="url(#color-4_wFfu6zXx15Yk_gr4)"></path></g></g>
                    </svg><br>
                    <h3 class="activity-title"><?php echo htmlspecialchars($configs['titulo_casinha_home']['valor'] ?? ''); ?></p></h3>
                    <p class="activity-description"><?php echo htmlspecialchars($configs['descricao_casinha_home']['valor'] ?? ''); ?></p></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Adoção -->
    <section class="adoption-section" id="adocao">
        <div class="container">
            <div class="adoption-content">
                <div class="adoption-image">
                    <div class="adoption-image-wrapper">
                        <img src="assets/images/uploads/<?php echo htmlspecialchars($configs['imagem_adote']['valor'] ?? 'Adote_Gatinho.jpg'); ?>" alt="Gatos para adoção">

                        <div class="paw-print paw-1">🐾</div>
                        <div class="paw-print paw-2">🐾</div>
                        <div class="paw-print paw-3">🐾</div>
                    </div>
                </div>
                <div class="adoption-text">
                    <h2 class="section-title"><?php echo htmlspecialchars($configs['titulo_adocao']['valor'] ?? ''); ?></h2>
                    <p class="adoption-description"><?php echo htmlspecialchars($configs['descricao_adocao']['valor'] ?? ''); ?></p>
                    <ul class="adoption-features">
                        <?php foreach ($topicos as $topico): ?>
                            <li>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#65C5B2" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php echo htmlspecialchars($topico['texto']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo htmlspecialchars($configs['link_formulario']['valor'] ?? ''); ?>" target="_blank" class="btn btn-primary">
                        Preencher Formulário de Adoção
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Onda entre a seção de Adoção e Histórias de Sucesso -->
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: -97px;">
        <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
    </svg>

    <!-- Histórias de Sucesso -->
    <section class="success-stories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo htmlspecialchars($configs['titulo_historias']['valor'] ?? ''); ?></h2>
                <p class="section-subtitle"><?php echo htmlspecialchars($configs['descricao_historias']['valor'] ?? ''); ?></p>
            </div>
            <div class="stories-carousel">
                <?php if (!empty($historias)): ?>
                    <?php foreach ($historias as $historia): ?>
                        <div class="story-card">
                            <div class="story-image">
                                <?php if (!empty($historia["imagem"])): ?>
                                    <img src="assets/images/uploads/<?php echo htmlspecialchars($historia["imagem"]); ?>" alt="<?php echo htmlspecialchars($historia["nome_gato"] ?? ''); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="story-content">
                    <h3 class="story-title"><?php echo htmlspecialchars($historia["nome_gato"]); ?></h3>

                        <?php if (!empty($historia["idade"])): ?>
                            <p class="story-text"><strong>Idade: </strong><?php echo htmlspecialchars($historia["idade"]); ?></p><br>
                        <?php endif; ?>

                        <?php if (!empty($historia["descricao"])): ?>
                            <p class="story-text"><?php echo htmlspecialchars($historia["descricao"]); ?></p><br>
                        <?php endif; ?>

                        <?php if (!empty($historia["data_adocao"]) && strtotime($historia["data_adocao"])): ?>
                        <?php $dataFormatada = date("d/m/Y", strtotime($historia["data_adocao"])); ?>
                        <p class="story-text">
                            <strong>Data de Adoção: </strong><?php echo htmlspecialchars($dataFormatada); ?>
                        </p><br>
                    <?php endif; ?>

                        <?php if (!empty($historia["historia"])): ?>
                            <p class="story-text"><strong>História: </strong><?php echo htmlspecialchars($historia["historia"]); ?></p><br>
                        <?php endif; ?>

                        <?php if (!empty($historia["nome_adotante"])): ?>
                            <p class="story-text"><strong>Nome do Adotante: </strong><?php echo htmlspecialchars($historia["nome_adotante"]); ?></p>
                        <?php endif; ?>
                    </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!--Onda do Contato em celular-->
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg); margin-bottom: -40px;">
        <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
    </svg>

    <!-- Seção de Contato -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <h2 class="section-title"><?php echo htmlspecialchars($configs['titulo_contato']['valor'] ?? ''); ?></h2>
                    <p class="contact-description"><?php echo htmlspecialchars($configs['descricao_contato']['valor'] ?? ''); ?></p>
                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#65C5B2" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <a href="mailto:<?php echo $configs['email_contato'] ?? ''; ?>" style = "text-decoration:none;">
                                    <?php echo htmlspecialchars($configs['email_contato']['valor'] ?? ''); ?>
                                </a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#65C5B2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.86 19.86 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2h3a2 2 0 0 1 2 1.72c.12.8.36 1.58.7 2.33a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.75-1.75a2 2 0 0 1 2.11-.45c.75.34 1.53.58 2.33.7a2 2 0 0 1 1.72 2z"></path>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <h4>Telefone</h4>
                                <p><?php echo !empty($configs['telefone_contato']['valor']) ? htmlspecialchars($configs['telefone_contato']['valor']) : 'Em breve'; ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#A8CF45" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <h4>Localização</h4>
                                <p><?php echo htmlspecialchars($configs['endereco']['valor'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#6CCB10" stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </div>
                            <div class="contact-details">
                                <h4>Redes Sociais</h4>
                                    <div class="social-links">
                                        <?php foreach ($redes as $rede): ?>
                                            <a href="<?php echo htmlspecialchars($rede['link']); ?>" target="_blank">
                                                <img 
                                                    src="assets/images/icones/<?php echo htmlspecialchars($rede['icone']); ?>" 
                                                    alt="<?php echo htmlspecialchars($rede['chave']); ?>"
                                                    class="social-icon"
                                                >
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-map">
                    <iframe src="<?php echo htmlspecialchars($configs['link_google_maps']['valor'] ?? ''); ?>"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,50 C200,20 400,80 600,50 C800,20 1000,80 1200,50 L1200,0 L0,0 Z" fill="#65C5B2"></path>
            </svg>
        </div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <img src="assets/images/logo/logo1_branco.png" alt="Logo ONG" class="footer-logo">
                    <p class="footer-text">Cuidando dos gatos do Parque Portugal com amor, responsabilidade e dedicação.</p>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Links Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="QuemSomos.php">Quem Somos</a></li>
                        <li><a href="#adocao">Adoção</a></li>
                        <li><a href="Eventos.php">Eventos</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Como Ajudar</h4>
                    <ul class="footer-links">
                        <li><a href="Voluntariado.php">Voluntariado</a></li>
                        <li><a href="Doacoes.php">Doações</a></li>
                        <li><a href="<?php echo $configs['link_apoia_se']['valor'] ?? ''; ?>" target="_blank">Amigos da Lagoa</a></li>
                        <li><a href="<?php echo $configs['link_petlove']['valor'] ?? ''; ?>" target="_blank">Petlove</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:<?php echo htmlspecialchars($configs['email_contato']['valor'] ?? ''); ?>">Email</a></li>
                        <li><a href="Contato.php">Fale Conosco</a></li>
                        <li><a href="Galeria.php">Galeria</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 ONG Gatos da Lagoa Taquaral. Todos os direitos reservados.</p>
                <p class="footer-cnpj">CNPJ: <?php echo htmlspecialchars($configs['cnpj']['valor'] ?? ''); ?></p>
                <p class="footer-credit">Desenvolvido voluntariamente por estudante(s) do polo UNIP (Campinas - Swift) do curso de Análise e Desenvolvimento de Sistemas.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>

</body>
</html>