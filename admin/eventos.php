<?php
require_once '../includes/admin_header.php';

$db = getDatabase();
$message = '';
$error = '';

// Adiciona tratamento de sessão para a mensagem de sucesso pós-redirecionamento
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Processar ações
if ($_POST) {

    $action = $_POST['action'] ?? '';

    if ($action === 'remove_image') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $db->prepare("SELECT imagem FROM eventos WHERE id = ?");
            $stmt->execute([$id]);
            $imagem = $stmt->fetchColumn();
            if ($imagem) {
                deleteImage($imagem); // Função que deleta o arquivo físico
                // Atualiza banco
                $stmt = $db->prepare("UPDATE eventos SET imagem = NULL WHERE id = ?");
                $stmt->execute([$id]);
            }
            $_SESSION['message'] = 'Imagem removida com sucesso!';
            header("Location: eventos.php?edit=$id");
            exit();
        }
    }
    
    if ($action === 'add' || $action === 'edit') {
        $titulo       = sanitize($_POST['titulo'] ?? '');
        $descricao    = sanitize($_POST['descricao'] ?? '');
        $data_evento  = $_POST['data_evento'] ?? '';
        $hora_evento  = $_POST['hora_evento'] ?? '';
        $local_evento = sanitize($_POST['local_evento'] ?? '');
        $id           = (int)($_POST['id'] ?? 0);
        
        if (empty($titulo) || empty($data_evento)) {
            $error = 'Título e data do evento são obrigatórios.';
        } else {
            $imagem = '';
            
            // Upload de imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = uploadImage($_FILES['imagem'], 'evento_');
                if (!$imagem) {
                    $error = 'Erro no upload da imagem. Verifique o formato e tamanho.';
                }
            }
            
            if (!$error) {
                if ($action === 'add') {
                    $sql = "INSERT INTO eventos (titulo, descricao, data_evento, hora_evento, local_evento, imagem, admin_id, ativo) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
                    $params = [$titulo, $descricao, $data_evento, $hora_evento, $local_evento, $imagem, $_SESSION['admin_id']];
                } else {
                    if ($imagem) {
                        // Deletar imagem antiga
                        $stmt = $db->prepare("SELECT imagem FROM eventos WHERE id = ?");
                        $stmt->execute([$id]);
                        $old_image = $stmt->fetchColumn();
                        if ($old_image) {
                            deleteImage($old_image);
                        }
                        
                        $sql = "UPDATE eventos 
                                   SET titulo = ?, descricao = ?, data_evento = ?, hora_evento = ?, local_evento = ?, imagem = ? 
                                 WHERE id = ?";
                        $params = [$titulo, $descricao, $data_evento, $hora_evento, $local_evento, $imagem, $id];
                    } else {
                        $sql = "UPDATE eventos 
                                   SET titulo = ?, descricao = ?, data_evento = ?, hora_evento = ?, local_evento = ? 
                                 WHERE id = ?";
                        $params = [$titulo, $descricao, $data_evento, $hora_evento, $local_evento, $id];
                    }
                }

                
                $stmt = $db->prepare($sql);
                if ($stmt->execute($params)) {
                    if ($action === 'edit') {
                        $_SESSION['message'] = 'Evento atualizado com sucesso!';
                        header('Location: eventos.php');
                        exit();
                    } else {
                        $message = 'Evento adicionado com sucesso!';
                    }
                } else {
                    $error = 'Erro ao salvar evento.';
                }

            }
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            // Deletar imagem
            $stmt = $db->prepare("SELECT imagem FROM eventos WHERE id = ?");
            $stmt->execute([$id]);
            $imagem = $stmt->fetchColumn();
            if ($imagem) {
                deleteImage($imagem);
            }
            
            $stmt = $db->prepare("UPDATE eventos SET ativo = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                $message = 'Evento removido com sucesso!';
            } else {
                $error = 'Erro ao remover evento.';
            }
        }
    }
}

// Buscar eventos
$stmt = $db->query("SELECT * FROM eventos WHERE ativo = 1 ORDER BY data_evento DESC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Evento para edição
$evento_edit = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM eventos WHERE id = ? AND ativo = 1");
    $stmt->execute([$edit_id]);
    $evento_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<main class="main-content">
    <div class="admin-header">
        <h1 class="admin-title">Gerenciar Eventos</h1>
        <a href="dashboard.php" class="btn btn-secondary">← Voltar</a>
    </div>
    
    <?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
<!-- Formulário principal -->
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $evento_edit ? 'edit' : 'add'; ?>">
    <?php if ($evento_edit): ?>
    <input type="hidden" name="id" value="<?php echo $evento_edit['id']; ?>">
    <?php endif; ?>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="titulo" class="form-label">Título * (obrigatório)</label>
            <input type="text" id="titulo" name="titulo" class="form-input" 
                   value="<?php echo htmlspecialchars($evento_edit['titulo'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="data_evento" class="form-label">Data do Evento * (obrigatório)</label>
            <input type="date" id="data_evento" name="data_evento" class="form-input" 
                   value="<?php echo $evento_edit['data_evento'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="hora_evento" class="form-label">Horário</label>
            <input type="time" id="hora_evento" name="hora_evento" class="form-input" 
                   value="<?php echo $evento_edit['hora_evento'] ?? ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="local_evento" class="form-label">Local</label>
            <input type="text" id="local_evento" name="local_evento" class="form-input" 
                   value="<?php echo htmlspecialchars($evento_edit['local_evento'] ?? ''); ?>">
        </div>
        
        <div class="form-group full-width">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-textarea"><?php echo htmlspecialchars($evento_edit['descricao'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group full-width">
            <label for="imagem" class="form-label">Imagem</label>
            <input type="file" id="imagem" name="imagem" class="form-input" accept="image/*">

            <?php if ($evento_edit && $evento_edit['imagem']): ?>
                <p style="margin-top: 10px;">
                    <img src="../assets/images/uploads/<?php echo $evento_edit['imagem']; ?>" 
                         alt="Imagem atual" style="max-width: 200px; border-radius: 8px;">
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="eventos-actions">
        <button type="submit" class="btn btn-primary">
            <?php echo $evento_edit ? 'Atualizar Evento' : 'Adicionar Evento'; ?>
        </button>
        <?php if ($evento_edit): ?>
            <a href="eventos.php" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
    </div>

    
</form>
<!-- Formulário separado para remover imagem -->
<?php if ($evento_edit && $evento_edit['imagem']): ?>
<form method="POST"
      onsubmit="return confirm('Deseja realmente remover a imagem?');">
    <input type="hidden" name="action" value="remove_image">
    <input type="hidden" name="id" value="<?php echo $evento_edit['id']; ?>">
    <button type="submit" class="btn btn-danger" style="margin-top: 10px;">
    Remover imagem
    </button>
</form>
<?php endif; ?>

    <br><br>

    <!-- Lista de Eventos -->
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
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        Nenhum evento cadastrado.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($eventos as $evento): ?>
                        <?php

                        ?>
                    <tr>
                        <td>
                            <?php if ($evento['imagem']): ?>
                            <img src="../assets/images/uploads/<?php echo $evento['imagem']; ?>" 
                                alt="<?php echo htmlspecialchars($evento['titulo']); ?>" class="event-image">
                            <?php else: ?>
                            <div style="width: 60px; height: 60px; background: var(--light-gray); border-radius: 8px; display: flex; align-items: center; justify-content: center;">📅</div>
                            <?php endif; ?>
                        </td>
                            <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                            <td><?php echo formatDateBR($evento['data_evento']); ?></td>
                            <td><?php echo htmlspecialchars($evento['local_evento']); ?></td>
                        <td>
                            <a href="?edit=<?php echo $evento['id']; ?>" class="btn edit-btn">Editar</a>
                            <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover este evento?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">
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
