<?php /** @var array $imagens */ ?>
<section class="success-stories" style="padding: 100px 0; background-color: var(--color-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nossa Galeria</h2>
            <p class="section-subtitle">Momentos especiais do nosso dia a dia e dos nossos gatinhos.</p>
        </div>



            <?php if (empty($imagens)): ?>

                <p style="text-align: center; font-size: 1.2rem;">
                    Nenhuma imagem encontrada na galeria.
                </p>

            <?php else: ?>

                <div class="stories-carousel">

                    <?php foreach ($imagens as $imagem): ?>

                        <article class="story-card">

                            <div
                                class="story-image"
                                data-image="/assets/images/uploads/<?php echo e($imagem['imagem']); ?>"
                                data-title="<?php echo e($imagem['titulo']); ?>"
                                data-description="<?php echo e($imagem['descricao'] ?? ''); ?>"
                                role="button"
                                tabindex="0"
                                aria-label="Abrir imagem: <?php echo e($imagem['titulo']); ?>"
                            >

                                <img
                                    src="/assets/images/uploads/<?php echo e($imagem['imagem']); ?>"
                                    alt="<?php echo e($imagem['titulo']); ?>"
                                    loading="lazy"
                                >

                                <div class="story-image-overlay">
                                    <span class="story-image-icon" aria-hidden="true">🔍</span>
                                    <span class="story-image-text">Ver imagem</span>
                                </div>

                            </div>

                            <div class="story-content">

                                <h3 class="story-title">
                                    <?php echo e($imagem['titulo']); ?>
                                </h3>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- ==========================================
                Modal da Galeria Pública
                ========================================== -->

            <div
                class="image-modal"
                id="imageModal"
                aria-hidden="true"
            >

                <div class="image-modal-backdrop"></div>

                <div
                    class="image-modal-content"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modalTitle"
                >

                    <button
                        type="button"
                        class="image-modal-close"
                        id="imageModalClose"
                        aria-label="Fechar imagem"
                    >
                        &times;
                    </button>

                    <div class="modal-image-wrapper">

                        <img
                            src=""
                            alt=""
                            id="modalImage"
                            class="modal-image"
                        >

                    </div>

                    <div class="modal-info">

                        <h2 id="modalTitle"></h2>

                        <p id="modalDescription"></p>

                    </div>

                </div>

            </div>
    </div>

<script src="/assets/js/script_galeria.js"></script>