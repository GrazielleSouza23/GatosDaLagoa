<?php /** @var array $configs */ ?>
<section class="activities" style="padding: 100px 0; background: linear-gradient(135deg, #f0fdf4 0%, #e8f8f5 100%);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo e($configs['titulo_doacoes']['valor'] ?? ''); ?></h2>
            <p class="section-subtitle"><?php echo e($configs['descricao_doacoes']['valor'] ?? ''); ?></p>
        </div>
        <div class="activities-grid">
            <div class="activity-card">
                <div class="activity-icon">
                    <img src="/assets/images/icones/pix.png" alt="Ícone PIX" style="width: 55px; height: 55px;">
                </div><br>
                <h3 class="activity-title">PIX</h3>
                <p class="activity-description" style="text-align: center;">
                    <strong><?php echo e($configs['titulo_pix']['valor'] ?? ''); ?> </strong>
                    <span id="pix"><?php echo e($configs['pix']['valor'] ?? ''); ?></span>
                </p><br>
                <button type="button" class="btn btn-secondary" id="btnCopiarPix" style="margin-top: 15px;">Copiar Chave PIX</button>
            </div>
            <div class="activity-card">
                <div class="activity-icon">
                    <img src="/assets/images/icones/banco.png" alt="Ícone Banco" style="width: 60px; height: 60px;">
                </div><br>
                <h3 class="activity-title">Conta Bancária</h3>
                <p class="activity-description"><strong>Banco:</strong> <?php echo e($configs['conta_banco']['valor'] ?? 'N/A'); ?></p><br>
                <img src="/assets/images/logo/logo_caixa.png" alt="Logo da CAIXA">
            </div>
            <div class="activity-card">
                <div class="activity-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 140" width="60" height="60">
                        <path fill="#65C5B2" d="M130.6 86.2l-5.8-.8c3.5-3.4 3.8-3.8 4.9-5.1l1.5-1.8a41.3 41.3 0 008.4-29.3c-1-9.6-5.2-17.6-12.2-23.3a37.8 37.8 0 00-28.6-6.2A45.4 45.4 0 0071 35.3c-.2.2-.5.3-.8.3a1 1 0 01-.9-.4 46.1 46.1 0 00-28.2-15.8 38.3 38.3 0 00-28.8 6.2A33.3 33.3 0 000 49.2a41.3 41.3 0 008.5 29.4C22 94.2 66.2 133.4 68.1 135l.5.5a2.2 2.2 0 003 0l.7-.6c.9-.8 23-20.6 40.5-37.5.8-.8 1.7-3.5.2-5-1-1.2-3.6-1-4.5-.1-13.8 13.2-30.4 28-37.5 34.5l-.2.1a1 1 0 01-1.5 0 874.1 874.1 0 01-55.4-53c-4.9-5.8-7.7-15.4-6.8-24 .5-5.4 2.6-13 9.7-18.6a31.1 31.1 0 0123.4-4.8 40 40 0 0127 17.6l1 1.4c.4.6 1 1 1.8.9.7 0 1.4-.4 1.8-1l1-1.5c6.5-9.7 16-16 27-17.6 9-1.4 18 .4 23.5 4.8A26.7 26.7 0 01133 50a34.8 34.8 0 01-8 25.7c-1.4 1.6-1.7 2-5 5.3l-.3-5.8c-.1-1.8-2.9-1.8-2.8 0 .3 3.6.4 7.3.5 11 0 .6.3 1.2 1 1.3l11.4 1.5c1.8.3 2.5-2.4.8-2.7z"/>
                    </svg>
                </div><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_apoia_se']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_apoia_se']['valor'] ?? ''); ?></p>
                <a href="<?php echo e($configs['link_apoia_se']['valor'] ?? '#'); ?>" target="_blank" class="btn btn-secondary" style="margin-top: 15px;">Apoiar Mensalmente</a>
            </div>
        </div>
    </div>
</section>

<section class="activities" style="padding: 100px 0; background-color: var(--color-white);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo e($configs['titulo_outras_formas']['valor'] ?? ''); ?></h2>
        </div>
        <div class="activities-grid">
            <div class="activity-card">
                <div class="activity-icon">
                    <img src="/assets/images/icones/tampa.png" alt="Ícone Tampa" style="width: 60px; height: 60px;">
                </div><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_tampinhas']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_tampinhas']['valor'] ?? ''); ?></p>
                <?php if (!empty($configs['link_tampinhas']['valor'])): ?>
                <a href="<?php echo e($configs['link_tampinhas']['valor']); ?>" target="_blank" class="btn btn-secondary" style="margin-top: 15px;">Acessar Link</a>
                <?php endif; ?>
            </div>
            <div class="activity-card">
                <div class="activity-icon">
                    <img src="/assets/images/logo/pet-love.svg" alt="Logo da PetLove">
                </div><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_petlove']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_petlove']['valor'] ?? ''); ?></p>
                <?php if (!empty($configs['link_petlove']['valor'])): ?>
                <a href="<?php echo e($configs['link_petlove']['valor']); ?>" target="_blank" class="btn btn-secondary" style="margin-top: 15px;">Comprar na Petlove</a>
                <?php endif; ?>
            </div>
            <div class="activity-card">
                <div class="activity-icon">
                    <img src="/assets/images/icones/banco.png" alt="Ícone Agenda" style="width: 60px; height: 60px;">
                </div><br>
                <h3 class="activity-title"><?php echo e($configs['titulo_feiras']['valor'] ?? ''); ?></h3>
                <p class="activity-description"><?php echo e($configs['descricao_feiras']['valor'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</section>
