<?php
/** @var array $admin */
/** @var array $stats */
/** @var array $eventosProximos */
/** @var array $historiasRecentes */
?>
    <div class="admin-header">
        <h1 class="admin-title">Dashboard</h1>
        <div class="admin-user-card">
            <img src="/assets/images/icones/user.png" alt="Administrador" />
            <div class="admin-user-details">
                <span class="admin-name">Bem vindo, <?php echo e($admin['nome'] ?? 'Administrador'); ?>!</span>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon">📅</div>
            <div class="stat-number"><?php echo (int) $stats['eventos']; ?></div>
            <div class="stat-label">Eventos</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">❤️</div>
            <div class="stat-number"><?php echo (int) $stats['historias']; ?></div>
            <div class="stat-label">Histórias de Sucesso</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🖼️</div>
            <div class="stat-number"><?php echo (int) $stats['galeria']; ?></div>
            <div class="stat-label">Fotos na Galeria</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🐱</div>
            <div class="stat-number">200+</div>
            <div class="stat-label">Gatos Cuidados</div>
        </div>
    </div>

    <div class="admin-card-grid">
        <div class="admin-card">
            <h2 class="section-title" style="margin-top: 0;">Próximos Eventos</h2>
            <?php if (!empty($eventosProximos)): ?>
            <ul class="item-list" style="list-style: none; padding: 0;">
                <?php foreach ($eventosProximos as $evento): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div class="item-title" style="font-weight: 600;"><?php echo e($evento['titulo']); ?></div>
                    <div class="item-date" style="font-size: 14px; color: var(--color-gray);"><?php echo e(formatDateBR($evento['data_evento'])); ?></div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p style="color: var(--color-gray);">Nenhum evento próximo cadastrado.</p>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <h2 class="section-title" style="margin-top: 0;">Histórias Recentes</h2>
            <?php if (!empty($historiasRecentes)): ?>
            <ul class="item-list" style="list-style: none; padding: 0;">
                <?php foreach ($historiasRecentes as $historia): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div class="item-title" style="font-weight: 600;"><?php echo e($historia['nome_gato']); ?></div>
                    <div class="item-date" style="font-size: 14px; color: var(--color-gray);">Adotado(a) em <?php echo e(formatDateTimeBR($historia['data_adocao'])); ?></div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p style="color: var(--color-gray);">Nenhuma história cadastrada.</p>
            <?php endif; ?>
        </div>
    </div>

    <h2 class="section-title" style="margin-top: 30px; margin-bottom: 20px;">Ações Rápidas</h2>
    <div class="admin-card-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        <a href="/admin/eventos?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="/assets/images/icones/adicao.png" alt="adicao"></div>
            <div class="stat-label">Novo Evento</div>
        </a>
        <a href="/admin/historias?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="/assets/images/icones/story.png" alt="livro"></div>
            <div class="stat-label">Nova História</div>
        </a>
        <a href="/admin/galeria?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="/assets/images/icones/camera.png" alt="camera"></div>
            <div class="stat-label">Adicionar Foto</div>
        </a>
        <a href="/admin/configuracoes" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="/assets/images/icones/config.png" alt="config"></div>
            <div class="stat-label">Configurações</div>
        </a>
    </div>
