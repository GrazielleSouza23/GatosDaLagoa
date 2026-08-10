<?php /** @var array $eventosFuturos */ ?>
<section class="success-stories" style="padding: 100px 0; background: var(--color-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Próximos Eventos</h2>
        </div>

        <?php if (!empty($eventosFuturos)): ?>
        <div class="eventos-grid">
            <?php foreach ($eventosFuturos as $evento): ?>
            <div class="evento-card">
                <?php if (!empty($evento['imagem'])): ?>
                <div class="evento-imagem">
                    <img src="/assets/images/uploads/<?php echo e($evento['imagem']); ?>" alt="<?php echo e($evento['titulo'] ?? ''); ?>">
                </div>
                <?php endif; ?>
                <div class="evento-conteudo">
                    <h3 class="evento-titulo"><?php echo e($evento['titulo'] ?? ''); ?></h3>
                    <p class="evento-texto">
                        <strong>Data:</strong> <?php echo e(formatDateBR($evento['data_evento'] ?? '')); ?><br>
                        <?php if (!empty($evento['hora_evento'])): ?>
                        <strong>Horário:</strong> <?php echo e($evento['hora_evento']); ?><br>
                        <?php endif; ?>
                        <?php if (!empty($evento['local_evento'])): ?>
                        <strong>Local:</strong> <?php echo e($evento['local_evento']); ?><br>
                        <?php endif; ?>
                    </p>
                    <p class="evento-texto" style="margin-top: 15px;"><?php echo e($evento['descricao'] ?? ''); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 40px 0;">
            <p style="font-size: 1.2rem; color: var(--color-gray);">Novos eventos serão divulgados em breve!</p>
        </div>
        <?php endif; ?>
    </div>
</section>
