<?php
require_once '../includes/header.php';

// Buscar configurações do site
$db = getDatabase();

$stmt = $db->query("SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave");

$configs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $chave = $row["chave"];

    $valor = $row["valor"];
    $descricao = $row["descricao"];

    $configs[$chave] = [
        'valor' => $valor ?? '',
        'descricao' => $descricao ?? '',
        'atualizacao' => $row['data_atualizacao']
    ];
}

// Buscar eventos próximos

//Esse comando permite aparecer apenas eventos que ainda IRÃO acontecer

// Data atual
$hoje = date('Y-m-d');

// Eventos futuros
$hoje = date('Y-m-d'); // garante formato compatível com DATE no MySQL

$sql = "SELECT 
            id, 
            titulo, 
            SUBSTRING(descricao, 1, 4000) AS descricao, 
            imagem, 
            hora_evento, 
            local_evento, 
            data_evento 
        FROM eventos 
        WHERE ativo = 1 
          AND data_evento >= :hoje 
        ORDER BY data_evento ASC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':hoje', $hoje);
$stmt->execute();
$eventosFuturos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

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
                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($evento['imagem']); ?>" alt="<?php echo htmlspecialchars($evento['titulo'] ?? ''); ?>">
                </div>
                <?php endif; ?>
                <div class="evento-conteudo">
                    <h3 class="evento-titulo"><?php echo htmlspecialchars($evento['titulo'] ?? ''); ?></h3>
                    <p class="evento-texto">
                        <strong>Data:</strong> <?php echo formatDateBR($evento['data_evento'] ?? ''); ?><br>
                        <?php if (!empty($evento['hora_evento'])): ?>
                        <strong>Horário:</strong> <?php echo htmlspecialchars($evento['hora_evento']); ?><br>
                        <?php endif; ?>
                        <?php if (!empty($evento['local_evento'])): ?>
                        <strong>Local:</strong> <?php echo htmlspecialchars($evento['local_evento']); ?><br>
                        <?php endif; ?>
                    </p>
                    <p class="evento-texto" style="margin-top: 15px;"><?php echo htmlspecialchars($evento['descricao'] ?? ''); ?></p>
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

<?php
require_once '../includes/footer.php';
?>