<?php
/** @var array $admin */
/** @var array $stats */
/** @var string $message */
/** @var string $error */
?>

    <div class="admin-header">
        <h1 class="admin-title">Meu Perfil</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (!empty($message)): ?><div class="message success"><?php echo e($message); ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="message error"><?php echo e($error); ?></div><?php endif; ?>

    <div class="profile-sections">
        <div class="profile-section">
            <h2 class="section-title">Informações do Perfil</h2>
            <div class="profile-info">
                <div class="profile-details">
                    <h3><?php echo e($admin['nome'] ?? ''); ?></h3>
                    <div class="profile-meta">
                        <strong>Email:</strong> <?php echo e($admin['email'] ?? ''); ?><br>
                        <strong>Cadastrado em:</strong> <?php echo e(formatDateTimeBR($admin['data_criacao'] ?? null)); ?><br>
                        <strong>Último login:</strong> <?php echo e(formatDateTimeBR($admin['ultimo_login'] ?? null)); ?>
                    </div>
                </div>
            </div><br>

            <form method="POST" action="/admin/perfil">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" id="nome" name="nome" class="form-input" value="<?php echo e($admin['nome'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-input" value="<?php echo e($admin['email'] ?? ''); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
            </form>
        </div><br><br>

        <div class="profile-section">
            <h2 class="section-title">Alterar Senha</h2>
            <form method="POST" action="/admin/perfil">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <input type="hidden" name="action" value="change_password">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="senha_atual" class="form-label">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="nova_senha" class="form-label">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" class="form-input" required>
                        <div class="password-requirements">Mínimo de 6 caracteres</div>
                    </div>
                    <div class="form-group">
                        <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Alterar Senha</button>
            </form>
        </div><br><br>

        <div class="profile-section">
            <h2 class="section-title">Suas Contribuições</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo (int) $stats['eventos']; ?></div>
                    <div class="stat-label">Eventos Criados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo (int) $stats['historias']; ?></div>
                    <div class="stat-label">Histórias Adicionadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo (int) $stats['galeria']; ?></div>
                    <div class="stat-label">Fotos na Galeria</div>
                </div>
            </div>
        </div>
    </div>
