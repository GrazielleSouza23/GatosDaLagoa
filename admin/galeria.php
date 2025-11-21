<?php
require_once '../includes/admin_header.php';

$db = getDatabase();
$message = '';
$error = '';

// Processar ações
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $titulo = sanitize($_POST['titulo'] ?? '');
        $descricao = sanitize($_POST['descricao'] ?? '');
        $categoria = sanitize($_POST['categoria'] ?? '');
        $id = (int)($_POST['id'] ?? 0);
        
        if (empty($titulo)) {
            $error = 'Título é obrigatório.';
        } else {
            $imagem = '';
            
            // Upload de imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'galeria_');
                if (!$imagem) {
                    $error = 'Erro no upload da imagem. Verifique o formato e tamanho.';
                }
            }
            
            if (!$error) {
                if ($action === 'add') {
                    if (!$imagem) {
                        $error = 'Imagem é obrigatória para adicionar à galeria.';
                    } else {
                        $sql = "INSERT INTO galeria (titulo, descricao, imagem, categoria, admin_id) VALUES (?, ?, ?, ?, ?)";
                        $params = [$titulo, $descricao, $imagem, $categoria, $_SESSION['admin_id']];
                    }
                } else {
                    if ($imagem) {
                        // Deletar imagem antiga
                        $stmt = $db->prepare("SELECT imagem FROM galeria WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_image = $stmt->fetchColumn();
                        if ($old_image) {
                            deleteImage($old_image);
                        }
                        
                        $sql = "UPDATE galeria SET titulo = ?, descricao = ?, imagem = ?, categoria = ? WHERE id = ?";
                        $params = [$titulo, $descricao, $imagem, $categoria, $id];
                    } else {
                        $sql = "UPDATE galeria SET titulo = ?, descricao = ?, categoria = ? WHERE id = ?";
                        $params = [$titulo, $descricao, $categoria, $id];
                    }
                }
                
                if (!$error) {
                    $stmt = $db->prepare($sql);
                    if ($stmt->execute($params)) {
                        $message = $action === 'add' ? 'Imagem adicionada à galeria!' : 'Imagem atualizada!';
                    } else {
                        $error = 'Erro ao salvar imagem.';
                    }
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Deletar imagem
            $stmt = $db->prepare("SELECT imagem FROM galeria WHERE id = ?");
            $stmt->execute([$id]);
            $imagem = $stmt->fetchColumn();
            if ($imagem) {
                deleteImage($imagem);
            }
            
            $stmt = $db->prepare("UPDATE galeria SET ativo = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                $message = 'Imagem removida da galeria!';
            } else {
                $error = 'Erro ao remover imagem.';
            }
        }
    }
}

// Buscar imagens da galeria
$stmt = $db->query("SELECT * FROM galeria WHERE ativo = 1 ORDER BY data_criacao DESC");
$galeria = $stmt->fetchAll();

// Imagem para edição
$imagem_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM galeria WHERE id = ? AND ativo = 1");
    $stmt->execute([$edit_id]);
    $imagem_edit = $stmt->fetch();
}
?>

<main class="main-content">
    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Galeria</h1>
        <a href="dashboard.php" class="btn btn-secondary">← Voltar</a>
    </div>
    
    <?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Formulário -->
    <div class="form-container">
        <h2><?php echo $imagem_edit ? 'Editar Imagem' : 'Adicionar Nova Imagem'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $imagem_edit ? 'edit' : 'add'; ?>">
            <?php if ($imagem_edit): ?>
            <input type="hidden" name="id" value="<?php echo $imagem_edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="titulo" class="form-label">Título *</label>
                    <input type="text" id="titulo" name="titulo" class="form-input" 
                           value="<?php echo htmlspecialchars($imagem_edit['titulo'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select id="categoria" name="categoria" class="form-select">
                        <option value="">Selecione uma categoria</option>
                        <option value="gatos" <?php echo ($imagem_edit['categoria'] ?? '') === 'gatos' ? 'selected' : ''; ?>>Gatos</option>
                        <option value="eventos" <?php echo ($imagem_edit['categoria'] ?? '') === 'eventos' ? 'selected' : ''; ?>>Eventos</option>
                        <option value="voluntarios" <?php echo ($imagem_edit['categoria'] ?? '') === 'voluntarios' ? 'selected' : ''; ?>>Voluntários</option>
                        <option value="casinha" <?php echo ($imagem_edit['categoria'] ?? '') === 'casinha' ? 'selected' : ''; ?>>Casinha</option>
                        <option value="adocoes" <?php echo ($imagem_edit['categoria'] ?? '') === 'adocoes' ? 'selected' : ''; ?>>Adoções</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-textarea"><?php echo htmlspecialchars($imagem_edit['descricao'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label for="imagem" class="form-label">Imagem <?php echo $imagem_edit ? '' : '*'; ?></label>
                    <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*" <?php echo $imagem_edit ? '' : 'required'; ?>>
                    <?php if ($imagem_edit && $imagem_edit['imagem']): ?>
                    <img src="../assets/images/uploads/<?php echo $imagem_edit['imagem']; ?>" 
                         alt="Imagem atual" class="image-preview">
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $imagem_edit ? 'Atualizar Imagem' : 'Adicionar Imagem'; ?>
                </button>
                <?php if ($imagem_edit): ?>
                <a href="galeria.php" class="btn btn-secondary" style="margin-top: 10px;">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div><br><br>
    
    <!-- Filtros -->
    <div class="category-filter">
        <button class="btn filter-btn active" onclick="filterGallery('all', this)">Todas</button>
        <button class="btn filter-btn" onclick="filterGallery('gatos', this)">Gatos</button>
        <button class="btn filter-btn" onclick="filterGallery('eventos', this)">Eventos</button>
        <button class="btn filter-btn" onclick="filterGallery('voluntarios', this)">Voluntários</button>
        <button class="btn filter-btn" onclick="filterGallery('casinha', this)">Casinha</button>
        <button class="btn filter-btn" onclick="filterGallery('adocoes', this)">Adoções</button>
    </div>

    
    <!-- Galeria -->
    <div class="gallery-grid">
        <?php if (empty($galeria)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--white); border-radius: 15px;">
            Nenhuma imagem na galeria.
        </div>
        <?php else: ?>
        <?php foreach ($galeria as $item): ?>
        <div class="admin-gallery-item" data-category="<?php echo strtolower(trim($item['categoria'])); ?>">

            <img src="../assets/images/uploads/<?php echo $item['imagem']; ?>" 
                 alt="<?php echo htmlspecialchars($item['titulo']); ?>" 
                 class="gallery-image"
                 onclick="openImageModal('../assets/images/uploads/<?php echo $item['imagem']; ?>', '<?php echo htmlspecialchars($item['titulo']); ?>')">
            
            <div class="gallery-content">
                <h3 class="gallery-title"><?php echo htmlspecialchars($item['titulo']); ?></h3>
                <?php if ($item['categoria']): ?>
                <div class="gallery-category"><?php echo ucfirst($item['categoria']); ?></div>
                <?php endif; ?>
                <?php if ($item['descricao']): ?>
                <div class="gallery-description"><?php echo htmlspecialchars($item['descricao']); ?></div>
                <?php endif; ?>
                
                <div class="gallery-actions">
                    <a href="galeria.php?edit=<?php echo $item['id']; ?>" class="btn editar-btn">Editar</a>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja remover esta imagem?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="btn deletar-btn">Remover</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,50 C200,20 400,80 600,50 C800,20 1000,80 1200,50 L1200,0 L0,0 Z" fill="#65C5B2"></path>
            </svg>
        </div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <img src="../assets/images/logo/logocomnome.png" alt="Logo ONG" class="footer-logo">
                    <p class="footer-text">Cuidando dos gatos do Parque Portugal com amor, responsabilidade e dedicação.</p>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Links Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="../index.php">Início</a></li>
                        <li><a href="../pages/QuemSomos.php">Quem Somos</a></li>
                        <li><a href="../index.php#adocao">Adoção</a></li>
                        <li><a href="../pages/Eventos.php">Eventos</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Como Ajudar</h4>
                    <ul class="footer-links">
                        <li><a href="../pages/Voluntariado.php">Voluntariado</a></li>
                        <li><a href="../pages/Doacoes.php">Doações</a></li>
                        <li><a href="https://apoia.se/gatosdalagoataquaral" target="_blank">Amigos da Lagoa</a></li>
                        <li><a href="https://bit.ly/gatosdalagoa" target="_blank">Petlove</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Contato</h4>
                    <ul class="footer-links">
                        <li><a href="mailto:gatosdalagoacampinas@gmail.com">Email</a></li>
                        <li><a href="../pages/Contato.php">Fale Conosco</a></li>
                        <li><a href="../pages/Galeria.php">Galeria</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 ONG Gatos da Lagoa Taquaral. Todos os direitos reservados.</p>
                <p class="footer-cnpj">CNPJ: 21.657.568/0001-50</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/script_galeria.js"></script>
</body>
</html>