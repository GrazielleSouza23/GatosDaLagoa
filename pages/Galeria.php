<?php
require_once '../includes/header.php';

// Buscar configurações do site
$db = getDatabase();

$stmt = $db->query("SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave");

$configs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $chave = $row['chave'];

    $valor = $row["valor"];
    $descricao = $row["descricao"];

    $configs[$chave] = [
        'valor' => $valor ?? '',
        'descricao' => $descricao ?? '',
        'atualizacao' => $row['data_atualizacao']
    ];
}

// Consulta SQL para unificar imagens da galeria, eventos e histórias de sucesso
$sql = "
    -- 1. Imagens da tabela 'galeria'
    SELECT 
        id, 
        titulo, 
        imagem, 
        categoria, 
        descricao, 
        data_criacao 
    FROM galeria 
    WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

    UNION ALL

    -- 2. Imagens da tabela 'eventos'
    SELECT 
        id, 
        titulo, 
        imagem, 
        'eventos' AS categoria, -- Define a categoria manualmente
        descricao, 
        data_criacao 
    FROM eventos 
    WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

    UNION ALL

    -- 3. Imagens da tabela 'historias_sucesso'
    SELECT 
        id, 
        nome_gato AS titulo,       -- Usa 'nome_gato' como 'titulo'
        imagem AS imagem,   -- Usa 'imagem' como 'imagem'
        'adocoes' AS categoria,    -- Define a categoria manualmente
        descricao, 
        data_criacao 
    FROM historias_sucesso 
    WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

    -- Ordena todas as imagens pela data de criação, da mais nova para a mais antiga
    ORDER BY data_criacao DESC
";

$stmt = $db->query($sql);
$todasAsImagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="success-stories" style="padding: 100px 0; background-color: var(--color-light);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nossa Galeria</h2>
            <p class="section-subtitle">Momentos especiais do nosso dia a dia e dos nossos gatinhos.</p>
        </div>

        <?php if (empty($todasAsImagens)): ?>
            <p style="text-align: center; font-size: 1.2rem;">Nenhuma imagem encontrada na galeria.</p>
        <?php else: ?>
            <div class="stories-carousel">
                <?php foreach($todasAsImagens as $imagem): ?>
                <div class="story-card">
                    <div class="story-image">
                        <img src="../assets/images/uploads/<?php echo htmlspecialchars($imagem['imagem']); ?>" alt="<?php echo htmlspecialchars($imagem['titulo']); ?>">
                    </div>
                    <div class="story-content">
                        <h3 class="story-title"><?php echo htmlspecialchars($imagem['titulo']); ?></h3>
                        </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
require_once '../includes/footer.php';
?>