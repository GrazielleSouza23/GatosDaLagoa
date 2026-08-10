<?php
/** @var array $eventos */
/** @var array|null $eventoEdit */
/** @var string $message */
/** @var string $error */
?>

    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Eventos</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (!empty($message)): ?><div class="message success"><?php echo e($message); ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="message error"><?php echo e($error); ?></div><?php endif; ?>

    <form method="POST" action="/admin/eventos" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $eventoEdit ? 'edit' : 'add'; ?>">
        <?php if ($eventoEdit): ?>
        <input type="hidden" name="id" value="<?php echo (int) $eventoEdit['id']; ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="titulo" class="form-label">Título * (obrigatório)</label>
                <input type="text" id="titulo" name="titulo" class="form-input" value="<?php echo e($eventoEdit['titulo'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_evento" class="form-label">Data do Evento * (obrigatório)</label>
                <input type="date" id="data_evento" name="data_evento" class="form-input" value="<?php echo e($eventoEdit['data_evento'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="hora_evento" class="form-label">Horário</label>
                <input type="time" id="hora_evento" name="hora_evento" class="form-input" value="<?php echo e($eventoEdit['hora_evento'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="local_evento" class="form-label">Local</label>
                <input type="text" id="local_evento" name="local_evento" class="form-input" value="<?php echo e($eventoEdit['local_evento'] ?? ''); ?>">
            </div>
            <div class="form-group full-width">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-textarea"><?php echo e($eventoEdit['descricao'] ?? ''); ?></textarea>
            </div>
            <div class="form-group full-width">
                <label for="imagem" class="form-label">Imagem</label>
                <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*">
                <?php if ($eventoEdit && $eventoEdit['imagem']): ?>
                <p style="margin-top: 10px;">
                    <img src="/assets/images/uploads/<?php echo e($eventoEdit['imagem']); ?>" alt="Imagem atual" style="max-width: 200px; border-radius: 8px;">
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="eventos-actions">
            <button type="submit" class="btn btn-primary"><?php echo $eventoEdit ? 'Atualizar Evento' : 'Adicionar Evento'; ?></button>
            <?php if ($eventoEdit): ?>
                <a href="/admin/eventos" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($eventoEdit && $eventoEdit['imagem']): ?>
    <form method="POST" action="/admin/eventos" onsubmit="return confirm('Deseja realmente remover a imagem?');">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="remove_image">
        <input type="hidden" name="id" value="<?php echo (int) $eventoEdit['id']; ?>">
        <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Remover imagem</button>
    </form>
    <?php endif; ?>

    <br><br>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Local</th>
                    <th class="col-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($eventos)): ?>
                <tr><td colspan="5" style="text-align: center; padding: 40px;">Nenhum evento cadastrado.</td></tr>
                <?php else: foreach ($eventos as $evento): ?>
                <tr>
                    <td>
                        <?php if ($evento['imagem']): ?>
                        <img src="/assets/images/uploads/<?php echo e($evento['imagem']); ?>" alt="<?php echo e($evento['titulo']); ?>" class="event-image">
                        <?php else: ?>
                        <div style="width: 60px; height: 60px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center;">📅</div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($evento['titulo']); ?></td>
                    <td><?php echo e(formatDateBR($evento['data_evento'])); ?></td>
                    <td><?php echo e($evento['local_evento']); ?></td>
                    <td>
                        <a href="/admin/eventos?edit=<?php echo (int) $evento['id']; ?>" class="btn edit-btn">Editar</a>
                        <form method="POST" action="/admin/eventos" onsubmit="return confirm('Tem certeza que deseja remover este evento?');" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int) $evento['id']; ?>">
                            <button type="submit" class="btn delete-btn">Remover</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>