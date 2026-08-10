<?php /** @var array $imagens */ ?>
<section class="success-stories" style="padding: 100px 0; background-color: var(--color-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nossa Galeria</h2>
            <p class="section-subtitle">Momentos especiais do nosso dia a dia e dos nossos gatinhos.</p>
        </div>

        <?php if (empty($imagens)): ?>
            <p style="text-align: center; font-size: 1.2rem;">Nenhuma imagem encontrada na galeria.</p>
        <?php else: ?>
            <div class="stories-carousel">
                <?php foreach ($imagens as $imagem): ?>
                <div class="story-card">
                    <div class="story-image">
                        <img src="/assets/images/uploads/<?php echo e($imagem['imagem']); ?>" alt="<?php echo e($imagem['titulo']); ?>">
                    </div>
                    <div class="story-content">
                        <h3 class="story-title"><?php echo e($imagem['titulo']); ?></h3>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
