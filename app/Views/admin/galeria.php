<?php
/** @var array $galeria */
/** @var array|null $imagemEdit */
/** @var string $message */
/** @var string $error */
$categorias = ['gatos', 'eventos', 'voluntarios', 'casinha', 'adocoes'];
?>
    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Galeria</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (!empty($message)): ?><div class="message success"><?php echo e($message); ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="message error"><?php echo e($error); ?></div><?php endif; ?>

    <div class="form-container">
        <h2><?php echo $imagemEdit ? 'Editar Imagem' : 'Adicionar Nova Imagem'; ?></h2>
        <form method="POST" action="/admin/galeria" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
            <input type="hidden" name="action" value="<?php echo $imagemEdit ? 'edit' : 'add'; ?>">
            <?php if ($imagemEdit): ?>
            <input type="hidden" name="id" value="<?php echo (int) $imagemEdit['id']; ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="titulo" class="form-label">Título *</label>
                    <input type="text" id="titulo" name="titulo" class="form-input" value="<?php echo e($imagemEdit['titulo'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select id="categoria" name="categoria" class="form-select">
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo e($cat); ?>" <?php echo ($imagemEdit['categoria'] ?? '') === $cat ? 'selected' : ''; ?>><?php echo e(ucfirst($cat)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-textarea"><?php echo e($imagemEdit['descricao'] ?? ''); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="imagem" class="form-label">Imagem <?php echo $imagemEdit ? '' : '*'; ?></label>
                    <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*" <?php echo $imagemEdit ? '' : 'required'; ?>>
                    <?php if ($imagemEdit && $imagemEdit['imagem']): ?>
                    <img src="/assets/images/uploads/<?php echo e($imagemEdit['imagem']); ?>" alt="Imagem atual" class="image-preview">
                    <?php endif; ?>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary"><?php echo $imagemEdit ? 'Atualizar Imagem' : 'Adicionar Imagem'; ?></button>
                <?php if ($imagemEdit): ?>
                <a href="/admin/galeria" class="btn btn-secondary" style="margin-top: 10px;">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div><br><br>

    <div class="category-filter">
        <button class="btn filter-btn active" onclick="filterGallery('all', this)">Todas</button>
        <?php foreach ($categorias as $cat): ?>
        <button class="btn filter-btn" onclick="filterGallery('<?php echo e($cat); ?>', this)"><?php echo e(ucfirst($cat)); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="gallery-grid">
        <?php if (empty($galeria)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--white); border-radius: 15px;">
            Nenhuma imagem na galeria.
        </div>
        <?php else: foreach ($galeria as $item): ?>
        <div class="admin-gallery-item" data-category="<?php echo e(strtolower(trim($item['categoria'] ?? ''))); ?>">
            <img src="/assets/images/uploads/<?php echo e($item['imagem']); ?>" alt="<?php echo e($item['titulo']); ?>" class="gallery-image"
                 onclick="openImageModal('/assets/images/uploads/<?php echo e($item['imagem']); ?>', '<?php echo e($item['titulo']); ?>')">
            <div class="gallery-content">
                <h3 class="gallery-title"><?php echo e($item['titulo']); ?></h3>
                <?php if ($item['categoria']): ?><div class="gallery-category"><?php echo e(ucfirst($item['categoria'])); ?></div><?php endif; ?>
                <?php if ($item['descricao']): ?><div class="gallery-description"><?php echo e($item['descricao']); ?></div><?php endif; ?>
                <div class="gallery-actions">
                    <a href="/admin/galeria?edit=<?php echo (int) $item['id']; ?>" class="btn editar-btn">Editar</a>
                    <form method="POST" action="/admin/galeria" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover esta imagem?');">
                        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                        <button type="submit" class="btn deletar-btn">Remover</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
<script src="/assets/js/script_galeria.js"></script>
