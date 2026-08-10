<?php
/** @var array $configs */
/** @var array $topicos */
/** @var array $historias */
/** @var array $redes */
?>
<section class="hero">
    <div class="hero-wave-top">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0 C150,80 350,0 600,50 C850,100 1050,20 1200,80 L1200,0 L0,0 Z" fill="#65C5B2"></path>
        </svg>
    </div>
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">Cuidando dos Gatos da <span class="highlight">Lagoa Taquaral</span></h1>
            <p class="hero-description"><?php echo e($configs['site_descricao']['valor'] ?? ''); ?></p>
            <div class="hero-buttons">
                <a href="#adocao" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    Adotar um Gatinho
                </a>
                <a href="/doacoes" class="btn btn-secondary">
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
                <img src="/assets/images/uploads/<?php echo e($configs['imagem_inicial']['valor'] ?? 'Gato_Inicial.png'); ?>" alt="Gato resgatado pela ONG" fetchpriority="high">
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

<section class="activities">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo e($configs['titulo_atividades']['valor'] ?? ''); ?></h2>
            <p class="section-subtitle"><?php echo e($configs['descricao_atividades']['valor'] ?? ''); ?></p>
        </div>
        <div class="activities-grid">
            <div class="activity-card">
                <img src="/assets/images/icones/icons-cat-bowl.png" style="width: 75px; height: 75px;" alt="Ícone de tigela de ração"><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_alimentadores_home']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_alimentadores_home']['valor'] ?? ''); ?></p>
            </div>
            <div class="activity-card">
                <img src="/assets/images/icones/animal.png" style="width: 75px; height: 75px;" alt="Ícone de castração"><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_castracao']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_castracao']['valor'] ?? ''); ?></p>
            </div>
            <div class="activity-card">
                <img src="/assets/images/icones/tampa.png" style="width: 75px; height: 75px;" alt="Ícone da casinha"><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_casinha_home']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_casinha_home']['valor'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="adoption-section" id="adocao">
    <div class="container">
        <div class="adoption-content">
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="/assets/images/uploads/<?php echo e($configs['imagem_adote']['valor'] ?? 'Adote_Gatinho.jpg'); ?>" alt="Gatos para adoção">
                    <div class="paw-print paw-1">🐾</div>
                    <div class="paw-print paw-2">🐾</div>
                    <div class="paw-print paw-3">🐾</div>
                </div>
            </div>
            <div class="adoption-text">
                <h2 class="section-title"><?php echo e($configs['titulo_adocao']['valor'] ?? ''); ?></h2>
                <p class="adoption-description"><?php echo e($configs['descricao_adocao']['valor'] ?? ''); ?></p>
                <ul class="adoption-features">
                    <?php foreach ($topicos as $topico): ?>
                        <li>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#65C5B2" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <?php echo e($topico['texto']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo e($configs['link_formulario']['valor'] ?? ''); ?>" target="_blank" class="btn btn-primary">
                    Preencher Formulário de Adoção
                </a>
            </div>
        </div>
    </div>
</section>

<svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: -97px;">
    <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
</svg>

<section class="success-stories">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo e($configs['titulo_historias']['valor'] ?? ''); ?></h2>
            <p class="section-subtitle"><?php echo e($configs['descricao_historias']['valor'] ?? ''); ?></p>
        </div>
        <div class="stories-carousel">
            <?php foreach ($historias as $historia): ?>
                <div class="story-card">
                    <div class="story-image">
                        <?php if (!empty($historia['imagem'])): ?>
                            <img src="/assets/images/uploads/<?php echo e($historia['imagem']); ?>" alt="<?php echo e($historia['nome_gato'] ?? ''); ?>">
                        <?php else: ?>
                            <div class="no-image"> Sem foto </div>
                        <?php endif; ?>
                    </div>
                    <div class="story-content">
                        <h3 class="story-title"><?php echo e($historia['nome_gato']); ?></h3>
                        <?php if (!empty($historia['idade'])): ?>
                            <p class="story-text"><strong>Idade: </strong><?php echo e($historia['idade']); ?></p><br>
                        <?php endif; ?>
                        <?php if (!empty($historia['descricao'])): ?>
                            <p class="story-text"><?php echo e($historia['descricao']); ?></p><br>
                        <?php endif; ?>
                        <?php if (!empty($historia['historia'])): ?>
                            <p class="story-text"><strong>História: </strong><?php echo e($historia['historia']); ?></p><br>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg); margin-bottom: -40px;">
    <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
</svg>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <h2 class="section-title"><?php echo e($configs['titulo_contato']['valor'] ?? ''); ?></h2>
                <p class="contact-description"><?php echo e($configs['descricao_contato']['valor'] ?? ''); ?></p>
                <div class="contact-items">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#65C5B2" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <a href="mailto:<?php echo e($configs['email_contato']['valor'] ?? ''); ?>" style="text-decoration:none;">
                                <?php echo e($configs['email_contato']['valor'] ?? ''); ?>
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
                            <h3>Telefone</h3>
                            <p><?php echo !empty($configs['telefone_contato']['valor']) ? e($configs['telefone_contato']['valor']) : 'Em breve'; ?></p>
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
                            <h3>Localização</h3>
                            <p><?php echo e($configs['endereco']['valor'] ?? ''); ?></p>
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
                            <h3>Redes Sociais</h3>
                            <div class="social-links">
                                <?php foreach ($redes as $rede): ?>
                                    <a href="<?php echo e($rede['link']); ?>" target="_blank">
                                        <img src="/assets/images/icones/<?php echo e($rede['icone']); ?>" alt="<?php echo e($rede['chave']); ?>" class="social-icon">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact-map">
                <iframe src="<?php echo e($configs['link_google_maps']['valor'] ?? ''); ?>" title="Localização no Google Maps" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>
