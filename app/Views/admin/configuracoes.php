<?php
/** @var array $configsList lista crua (id, chave, valor, descricao) */
/** @var array $redes */
/** @var array $topicos */
/** @var string $message */
/** @var string $error */
?>
    <div class="admin-header">
        <h1 class="admin-title">Configurações do Site</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (!empty($message)): ?><div class="message success"><?php echo e($message); ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="message error"><?php echo e($error); ?></div><?php endif; ?>

    <div class="form-container">
        <h2>Textos e Links do Site</h2>
        <p style="color: var(--color-gray); margin-bottom: 15px;">
            Estes valores alimentam automaticamente todas as páginas públicas (Home, Quem Somos, Eventos, Doações, Voluntariado, Contato).
        </p>
        <form method="POST" action="/admin/configuracoes">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
            <input type="hidden" name="action" value="update_configs">
            <div class="form-grid">
                <?php foreach ($configsList as $item): ?>
                <div class="form-group <?php echo strlen($item['valor'] ?? '') > 80 ? 'full-width' : ''; ?>">
                    <label for="cfg_<?php echo e($item['chave']); ?>" class="form-label">
                        <?php echo e($item['descricao'] ?: $item['chave']); ?>
                        <small style="color: var(--color-gray); font-weight: 400;">(<?php echo e($item['chave']); ?>)</small>
                    </label>
                    <?php if (strlen($item['valor'] ?? '') > 80): ?>
                    <textarea id="cfg_<?php echo e($item['chave']); ?>" name="config[<?php echo e($item['chave']); ?>]" class="form-textarea"><?php echo e($item['valor'] ?? ''); ?></textarea>
                    <?php else: ?>
                    <input type="text" id="cfg_<?php echo e($item['chave']); ?>" name="config[<?php echo e($item['chave']); ?>]" class="form-input" value="<?php echo e($item['valor'] ?? ''); ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Salvar Configurações</button>
        </form>
    </div>

    <br><br>

    <div class="form-container">
        <h2>Redes Sociais</h2>
        <form method="POST" action="/admin/configuracoes" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
            <input type="hidden" name="action" value="add_rede">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Chave (ex: instagram)</label>
                    <input type="text" name="chave" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ícone</label>
                    <input type="file" name="icone" class="form-input" accept=".png,.jpg,.jpeg,.svg,.webp" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Link</label>
                    <input type="url" name="link" class="form-input" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Adicionar Rede Social</button>
        </form>

        <div class="admin-table-container" style="margin-top: 20px;">
            <table class="admin-table">
                <thead><tr><th>Ícone</th><th>Chave</th><th>Link</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                <?php if (empty($redes)): ?>
                    <tr><td colspan="4" style="text-align:center; padding: 20px;">Nenhuma rede social cadastrada.</td></tr>
                <?php else: foreach ($redes as $rede): ?>
                    <tr>
                        <td><img src="/assets/images/icones/<?php echo e($rede['icone']); ?>" alt="<?php echo e($rede['chave']); ?>" style="width:24px;height:24px;"></td>
                        <td><?php echo e($rede['chave']); ?></td>
                        <td><a href="<?php echo e($rede['link']); ?>" target="_blank"><?php echo e($rede['link']); ?></a></td>
                        <td>
                            <form method="POST" action="/admin/configuracoes" onsubmit="return confirm('Remover esta rede social?');" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                <input type="hidden" name="action" value="delete_rede">
                                <input type="hidden" name="id" value="<?php echo (int) $rede['id']; ?>">
                                <button type="submit" class="btn delete-btn">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <br><br>

    <div class="form-container">
        <h2>Tópicos da Seção "Adote um Gatinho"</h2>
        <form method="POST" action="/admin/configuracoes">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
            <input type="hidden" name="action" value="add_topico">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Texto do tópico</label>
                    <input type="text" name="texto" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ordem</label>
                    <input type="number" name="ordem" class="form-input" value="0">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Adicionar Tópico</button>
        </form>

        <div class="admin-table-container" style="margin-top: 20px;">
            <table class="admin-table">
                <thead><tr><th>Ordem</th><th>Texto</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                <?php if (empty($topicos)): ?>
                    <tr><td colspan="3" style="text-align:center; padding: 20px;">Nenhum tópico cadastrado.</td></tr>
                <?php else: foreach ($topicos as $topico): ?>
                    <tr>
                        <td><?php echo (int) $topico['ordem']; ?></td>
                        <td><?php echo e($topico['texto']); ?></td>
                        <td>
                            <form method="POST" action="/admin/configuracoes" onsubmit="return confirm('Remover este tópico?');" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                <input type="hidden" name="action" value="delete_topico">
                                <input type="hidden" name="id" value="<?php echo (int) $topico['id']; ?>">
                                <button type="submit" class="btn delete-btn">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
