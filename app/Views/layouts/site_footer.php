    </main>
    <footer class="footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,50 C200,20 400,80 600,50 C800,20 1000,80 1200,50 L1200,0 L0,0 Z" fill="#65C5B2"></path>
            </svg>
        </div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <img src="/assets/images/logo/logo1_branco.png" alt="Logo ONG" class="footer-logo" width="150" height="50">
                    <p class="footer-text">Cuidando dos gatos do Parque Portugal com amor, responsabilidade e dedicação.</p>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Links Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="/">Início</a></li>
                        <li><a href="/quem-somos">Quem Somos</a></li>
                        <li><a href="/#adocao">Adoção</a></li>
                        <li><a href="/eventos">Eventos</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Como Ajudar</h4>
                    <ul class="footer-links">
                        <li><a href="/voluntariado">Voluntariado</a></li>
                        <li><a href="/doacoes">Doações</a></li>
                        <li><a href="<?php echo e($configs['link_apoia_se']['valor'] ?? ''); ?>" target="_blank">Amigos da Lagoa</a></li>
                        <li><a href="<?php echo e($configs['link_petlove']['valor'] ?? ''); ?>" target="_blank">Petlove</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:<?php echo e($configs['email_contato']['valor'] ?? ''); ?>">Email</a></li>
                        <li><a href="/contato">Fale Conosco</a></li>
                        <li><a href="/galeria">Galeria</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> ONG Gatos da Lagoa Taquaral. Todos os direitos reservados.</p>
                <p class="footer-cnpj">CNPJ: <?php echo e($configs['cnpj']['valor'] ?? ''); ?></p>
                <p class="footer-credit">Desenvolvido voluntariamente por estudante(s) do polo UNIP (Campinas - Swift) do curso de Análise e Desenvolvimento de Sistemas.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/script.js"></script>
</body>
</html>
