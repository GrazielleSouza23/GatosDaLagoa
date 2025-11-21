<?php
require_once '../includes/admin_header.php';

$db = getDatabase();

// Estatísticas
$stats = [];

// Total de eventos
$stmt = $db->query("SELECT COUNT(*) AS TOTAL FROM eventos WHERE ativo = 1");
$stats['eventos'] = $stmt->fetch()['TOTAL'];


// Total de histórias de sucesso
$stmt = $db->query("SELECT COUNT(*) AS TOTAL FROM historias_sucesso WHERE ativo = 1");
$stats['historias'] = $stmt->fetch()['TOTAL'];

// Total de imagens na galeria
$stmt = $db->query("SELECT COUNT(*) AS TOTAL FROM galeria WHERE ativo = 1");
$stats['galeria'] = $stmt->fetch()['TOTAL'];

// Eventos próximos
$stmt = $db->prepare("
    SELECT * FROM eventos 
    WHERE ativo = 1 AND data_evento >= CURDATE()
    ORDER BY data_evento ASC
    LIMIT 5
");

$stmt->execute();
$eventos_proximos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Histórias recentes
$stmt = $db->prepare("
    SELECT * FROM historias_sucesso 
    WHERE ativo = 1 
    ORDER BY data_adocao DESC
    LIMIT 5
");
$stmt->execute();
$historias_recentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<main class="main-content">
    <div class="admin-header">
    <h1 class="admin-title">Dashboard</h1>
        <div class="admin-user-card">
            <img src="../assets/images/icones/user.png" alt="Administrador" />
            <div class="admin-user-details">
                <span class="admin-name">Bem vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</span>
            </div>
        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-item"> 
            <div class="stat-icon">📅</div>
            <div class="stat-number"><?php echo $stats['eventos']; ?></div>
            <div class="stat-label">Eventos</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">❤️</div>
            <div class="stat-number"><?php echo $stats['historias']; ?></div>
            <div class="stat-label">Histórias de Sucesso</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🖼️</div>
            <div class="stat-number"><?php echo $stats['galeria']; ?></div>
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
            <?php if (!empty($eventos_proximos)): ?>
            <ul class="item-list" style="list-style: none; padding: 0;">
                <?php foreach ($eventos_proximos as $evento): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div>
                        <div class="item-title" style="font-weight: 600;"><?php echo htmlspecialchars($evento['titulo']); ?></div>
                        <div class="item-date" style="font-size: 14px; color: var(--color-gray);"><?php echo formatDateBR($evento['data_evento']); ?></div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p style="color: var(--color-gray);">Nenhum evento próximo cadastrado.</p>
            <?php endif; ?>
        </div>
        
        <div class="admin-card">
            <h2 class="section-title" style="margin-top: 0;">Histórias Recentes</h2>
            <?php if (!empty($historias_recentes)): ?>
            <ul class="item-list" style="list-style: none; padding: 0;">
                <?php foreach ($historias_recentes as $historia): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div>
                        <div class="item-title" style="font-weight: 600;"><?php echo htmlspecialchars($historia['nome_gato']); ?></div>
                        <div class="item-date" style="font-size: 14px; color: var(--color-gray);">Adotado(a) em <?php echo formatDateTimeBR($historia['data_adocao']); ?></div>
                    </div>
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
        <a href="eventos.php?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="../assets/images/icones/adicao.png" alt="adicao"></div>
            <div class="stat-label">Novo Evento</div>
        </a>
        <a href="historias.php?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="../assets/images/icones/story.png" alt="livro"></div>
            <div class="stat-label">Nova História</div>
        </a>
        <a href="galeria.php?add=1" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="../assets/images/icones/camera.png" alt="camera"></div>
            <div class="stat-label">Adicionar Foto</div>
        </a>
        <a href="configuracoes.php" class="stat-item" style="cursor: pointer;">
            <div class="stat-number"><img src="../assets/images/icones/config.png" alt="config"></div>
            <div class="stat-label">Configurações</div>
        </a>
    </div>
</main>

<?php
require_once '../includes/admin_footer.php';
?>
