<?php /** @var array $configs */ ?>
<section class="quem-somos-section" style="padding: 100px 0; background: linear-gradient(135deg, #e8f8f5 0%, #f0fdf4 100%);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Quem Somos</h2>
        </div>
        <div class="adoption-content">
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="/assets/images/uploads/<?php echo e($configs['imagem_missao']['valor'] ?? 'Gato1.jpg'); ?>" alt="Voluntária cuidando de um gato">
                </div>
            </div>
            <div class="adoption-text">
                <h3><?php echo e($configs['titulo_missao']['valor'] ?? ''); ?></h3>
                <p class="adoption-description"><?php echo e($configs['missao']['valor'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</section>

<svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="margin-top: -38px; position: absolute;">
    <path d="M0,50 C200,100 400,20 600,70 C800,120 1000,40 1200,90 L1200,120 L0,120 Z" fill="#ffffff"></path>
</svg>

<section style="padding: 100px 0; background: var(--color-white);">
    <div class="container">
        <div class="adoption-content alternate-layout">
            <div class="adoption-text">
                <h3><?php echo e($configs['titulo_reconhecimento']['valor'] ?? ''); ?></h3>
                <p class="adoption-description"><?php echo e($configs['reconhecimento']['valor'] ?? ''); ?></p>
            </div>
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="/assets/images/uploads/<?php echo e($configs['imagem_reconhecimento']['valor'] ?? 'Gato2.jpg'); ?>" alt="Documento de utilidade pública">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="wave-top-lower">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <path d="M0,10 C200,80 400,10 600,50 C800,90 1000,20 1200,60 L1200,120 L0,120 Z" fill="#F5F5F5"></path>
    </svg>
</div>

<section style="padding: 70px 0; background: var(--color-light);">
    <div class="container">
        <div class="adoption-content">
            <div class="adoption-image">
                <div class="adoption-image-wrapper">
                    <img src="/assets/images/uploads/<?php echo e($configs['imagem_trabalho']['valor'] ?? 'Gato3.jpeg'); ?>" alt="Ponto de alimentação para gatos no parque">
                </div>
            </div>
            <div class="adoption-text">
                <h3><?php echo e($configs['titulo_trabalho']['valor'] ?? 'Nosso Trabalho'); ?></h3>
                <p class="adoption-description"><?php echo e($configs['trabalho']['valor'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</section>
