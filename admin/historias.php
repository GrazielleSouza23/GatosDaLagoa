<?php
require_once '../includes/admin_header.php';

$db = getDatabase();
$message = '';
$error = '';

// Processar ações
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $nome_gato     = sanitize($_POST['nome_gato'] ?? '');
        $idade         = sanitize($_POST['idade'] ?? '');
        $descricao     = sanitize($_POST['descricao'] ?? '');
        $historia      = sanitize($_POST['historia'] ?? '');
        $data_adocao   = $_POST['data_adocao'] ?? null;
        $nome_adotante = sanitize($_POST['nome_adotante'] ?? '');
        $id            = (int)($_POST['id'] ?? 0);
        
        if (empty($nome_gato) || empty($descricao)) {
            $error = 'Nome do gato e descrição são obrigatórios.';
        } else {
            $imagem = '';
            
            // Upload de imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'historia_');
                if (!$imagem) {
                    $error = 'Erro no upload da imagem. Verifique o formato e tamanho.';
                }
            }
            
            if (!$error) {
                if ($action === 'add') {
                    $sql = "INSERT INTO historias_sucesso 
                               (nome_gato, idade, descricao, historia, imagem, data_adocao, nome_adotante, admin_id, ativo, data_criacao) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())";
                    $params = [$nome_gato, $idade, $descricao, $historia, $imagem, $data_adocao ?: null, $nome_adotante, $_SESSION['admin_id']];
                } else {
                    // Atualização
                    $update_fields = [
                        'nome_gato = ?',
                        'idade = ?',
                        'descricao = ?',
                        'historia = ?',
                        'data_adocao = ?',
                        'nome_adotante = ?'
                    ];
                    $params = [$nome_gato, $idade, $descricao, $historia, $data_adocao ?: null, $nome_adotante];

                    // Buscar imagens antigas para deletar se necessário
                    $stmt = $db->prepare("SELECT imagem FROM historias_sucesso WHERE id = ?");
                    $stmt->execute([$id]);
                    $old_images = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($imagem) {
                        if (!empty($old_images['imagem'])) {
                            deleteImage($old_images['imagem']);
                        }
                        $update_fields[] = 'imagem = ?';
                        $params[] = $imagem;
                    }

                    $params[] = $id;
                    $sql = "UPDATE historias_sucesso SET " . implode(', ', $update_fields) . " WHERE id = ?";
                }
                
                $stmt = $db->prepare($sql);
                if ($stmt->execute($params)) {
                    $message = $action === 'add' ? 'História adicionada com sucesso!' : 'História atualizada com sucesso!';
                } else {
                    $error = 'Erro ao salvar história.';
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Deletar imagens
            $stmt = $db->prepare("SELECT imagem FROM historias_sucesso WHERE id = ?");
            $stmt->execute([$id]);
            $images = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($images['imagem'])) {
                deleteImage($images['imagem']);
            }

            $stmt = $db->prepare("UPDATE historias_sucesso SET ativo = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                $message = 'História removida com sucesso!';
            } else {
                $error = 'Erro ao remover história.';
            }
        }
    }
}

// Buscar histórias do banco
$stmt = $db->query("SELECT * FROM historias_sucesso WHERE ativo = 1 ORDER BY data_criacao DESC");
$historias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// História para edição
$historia_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM historias_sucesso WHERE id = ? AND ativo = 1");
    $stmt->execute([$edit_id]);
    $historia_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

        
    <!-- Main Content -->
    <main class="main-content">
    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Histórias de Sucesso</h1>
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
        <h2><?php echo $historia_edit ? 'Editar História' : 'Nova História de Sucesso'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $historia_edit ? 'edit' : 'add'; ?>">
            <?php if ($historia_edit): ?>
            <input type="hidden" name="id" value="<?php echo $historia_edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nome_gato" class="form-label">Nome do Gato * (obrigatório)</label>
                    <input type="text" id="nome_gato" name="nome_gato" class="form-input" value="<?php echo htmlspecialchars($historia_edit['nome_gato'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="idade" class="form-label">Idade</label>
                    <input type="text" id="idade" name="idade" class="form-input" value="<?php echo htmlspecialchars($historia_edit['idade'] ?? ''); ?>" placeholder="Ex: 2 anos">
                </div>
                
                <div class="form-group">
                    <label for="data_adocao" class="form-label">Data da Adoção</label>
                    <input type="date" id="data_adocao" name="data_adocao" class="form-input" value="<?php echo htmlspecialchars($historia_edit['data_adocao'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nome_adotante" class="form-label">Nome do Adotante</label>
                    <input type="text" id="nome_adotante" name="nome_adotante" class="form-input" value="<?php echo htmlspecialchars($historia_edit['nome_adotante'] ?? ''); ?>">
                </div>
                
                <div class="form-group full-width">
                    <label for="descricao" class="form-label">Descrição Breve * (obrigatório)</label>
                    <textarea id="descricao" name="descricao" class="form-textarea" style="min-height: 120px;"><?php echo htmlspecialchars($historia_edit['descricao'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full-width">
                    <label for="historia" class="form-label">História Completa</label>
                    <textarea id="historia" name="historia" class="form-textarea" style="min-height: 120px;"><?php echo htmlspecialchars($historia_edit['historia'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="imagem" class="form-label">Imagem</label>
                    <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*">
                    <?php if (!empty($historia_edit['imagem'])): ?>
                    <img src="../assets/images/uploads/<?php echo htmlspecialchars($historia_edit['imagem']); ?>" 
                         alt="Imagem" class="image-preview">
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $historia_edit ? 'Atualizar História' : 'Adicionar História'; ?>
                </button>
                <?php if ($historia_edit): ?>
                <a href="historias.php" class="btn btn-secondary" style="margin-left: 10px;">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Lista de Histórias -->
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Gato</th>
                    <th>Idade</th>
                    <th>Adotante</th>
                    <th>Data Adoção</th>
                    <th class="col-actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($historias)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        Nenhuma história de sucesso cadastrada.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($historias as $historia): ?>
                        <tr>
                            <td>
                                <?php if (!empty($historia['imagem'])): ?>
                                    <img src="../assets/images/uploads/<?php echo $historia['imagem']; ?>" 
                                        alt="<?php echo htmlspecialchars($historia['nome_gato']); ?>" class="event-image">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center;">🐱</div>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($historia['nome_gato']); ?>
                            </td>

                            <td><?php echo htmlspecialchars($historia['idade'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($historia['nome_adotante'] ?? ''); ?></td>
                            <td><?php echo isset($historia['data_adocao']) ? formatDateBR($historia['data_adocao']) : ''; ?></td>

                            <td>
                                <a href="?edit=<?php echo $historia['id']; ?>" class="btn edit-btn">Editar</a>
                                <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta história?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $historia['id']; ?>">
                                    <button type="submit" class="btn delete-btn">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
require_once '../includes/admin_footer.php';
?>
