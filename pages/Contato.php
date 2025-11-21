<?php
require_once '../includes/header.php';
?>

    <!-- Conteúdo específico da página de Contato -->
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
                                                    src="../assets/images/icones/<?php echo htmlspecialchars($rede['icone']); ?>" 
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

<?php
require_once '../includes/footer.php';
?>
