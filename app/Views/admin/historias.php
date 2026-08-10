<?php
/** @var array $historias */
/** @var array|null $historiaEdit */
/** @var string $message */
/** @var string $error */
?>
    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Histórias de Sucesso</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (!empty($message)): ?><div class="message success"><?php echo e($message); ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="message error"><?php echo e($error); ?></div><?php endif; ?>

    <form method="POST" action="/admin/historias" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $historiaEdit ? 'edit' : 'add'; ?>">
        <?php if ($historiaEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $historiaEdit['id']; ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="nome_gato" class="form-label">Nome do Gato * (obrigatório)</label>
                <input type="text" id="nome_gato" name="nome_gato" class="form-input" value="<?php echo e($historiaEdit['nome_gato'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="idade" class="form-label">Idade</label>
                <input type="text" id="idade" name="idade" class="form-input" value="<?php echo e($historiaEdit['idade'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="nome_adotante" class="form-label">Nome do Adotante</label>
                <input type="text" id="nome_adotante" name="nome_adotante" class="form-input" value="<?php echo e($historiaEdit['nome_adotante'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="data_adocao" class="form-label">Data de Adoção</label>
                <input type="date" id="data_adocao" name="data_adocao" class="form-input" value="<?php echo e($historiaEdit['data_adocao'] ?? ''); ?>">
            </div>
            <div class="form-group full-width">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-textarea"><?php echo e($historiaEdit['descricao'] ?? ''); ?></textarea>
            </div>
            <div class="form-group full-width">
                <label for="historia" class="form-label">História</label>
                <textarea id="historia" name="historia" class="form-textarea"><?php echo e($historiaEdit['historia'] ?? ''); ?></textarea>
            </div>
            <div class="form-group full-width">
                <label for="imagem" class="form-label">Imagem </label>
                <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*">
                <?php if ($historiaEdit && $historiaEdit['imagem']): ?>
                <p style="margin-top: 10px;">
                    <img src="/assets/images/uploads/<?php echo e($historiaEdit['imagem']); ?>" alt="Imagem atual" style="max-width: 200px; border-radius: 8px;">
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="eventos-actions">
            <button type="submit" class="btn btn-primary"><?php echo $historiaEdit ? 'Atualizar História' : 'Adicionar História'; ?></button>
            <?php if ($historiaEdit): ?>
                <a href="/admin/historias" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($historiaEdit && $historiaEdit['imagem']): ?>
    <form method="POST" action="/admin/historias" onsubmit="return confirm('Deseja realmente remover a imagem?');">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="remove_image">
        <input type="hidden" name="id" value="<?php echo (int) $historiaEdit['id']; ?>">
        <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Remover imagem</button>
    </form>
    <?php endif; ?>

    <br><br>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Gato</th>
                    <th>Adotante</th>
                    <th>Data Adoção</th>
                    <th class="col-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historias)): ?>
                <tr><td colspan="5" style="text-align: center; padding: 40px;">Nenhuma história cadastrada.</td></tr>
                <?php else: foreach ($historias as $historia): ?>
                <tr>
                    <td>
                        <?php if ($historia['imagem']): ?>
                        <img src="/assets/images/uploads/<?php echo e($historia['imagem']); ?>" alt="<?php echo e($historia['nome_gato']); ?>" class="event-image">
                        <?php else: ?>
                        <div style="width: 60px; height: 60px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center;">Sem foto</div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($historia['nome_gato']); ?></td>
                    <td><?php echo e($historia['nome_adotante']); ?></td>
                    <td><?php echo e(formatDateBR($historia['data_adocao'])); ?></td>
                    <td>
                        <a href="/admin/historias?edit=<?php echo (int) $historia['id']; ?>" class="btn edit-btn">Editar</a>
                        <form method="POST" action="/admin/historias" onsubmit="return confirm('Tem certeza que deseja remover esta história?');" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int) $historia['id']; ?>">
                            <button type="submit" class="btn delete-btn">Remover</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
