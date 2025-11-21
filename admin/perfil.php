<?php
require_once '../includes/admin_header.php';

$db = getDatabase(); // conexão vinda do config.php
$message = '';
$error = '';

// Buscar dados do administrador atual
$stmt = $db->prepare("SELECT nome, email, data_criacao, ultimo_login, senha FROM administradores WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    $admin = [
        'nome' => '',
        'email' => '',
        'data_criacao' => null,
        'ultimo_login' => null
    ];
    $error = 'Administrador não encontrado.';
}

// Processar atualizações
if ($_POST) {    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $nome  = sanitize($_POST['nome'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        
        if (empty($nome) || empty($email)) {
            $error = 'Nome e email são obrigatórios.';
        } elseif (!validateEmail($email)) {
            $error = 'Email inválido.';
        } else {
            // Verificar se email já existe (exceto o atual)
            $stmt = $db->prepare("SELECT id FROM administradores WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['admin_id']]);
            if ($stmt->fetch()) {
                $error = 'Este email já está sendo usado por outro administrador.';
            } else {
                $stmt = $db->prepare("UPDATE administradores SET nome = ?, email = ? WHERE id = ?");
                if ($stmt->execute([$nome, $email, $_SESSION['admin_id']])) {
                    $_SESSION['admin_nome']  = $nome;
                    $_SESSION['admin_email'] = $email;
                    $message = 'Perfil atualizado com sucesso!';
                    
                    // Recarregar dados
                    $stmt = $db->prepare("SELECT nome, email, data_criacao, ultimo_login, senha FROM administradores WHERE id = ?");
                    $stmt->execute([$_SESSION['admin_id']]);
                    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $error = 'Erro ao atualizar perfil.';
                }
            }
        }
    }
    elseif ($action === 'change_password') {
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';
        
        if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
            $error = 'Todos os campos de senha são obrigatórios.';
        } elseif ($nova_senha !== $confirmar_senha) {
            $error = 'A nova senha e a confirmação não coincidem.';
        } elseif (strlen($nova_senha) < 6) {
            $error = 'A nova senha deve ter pelo menos 6 caracteres.';
        } elseif (!verifyPassword($senha_atual, $admin['senha'])) {
            $error = 'Senha atual incorreta.';
        } else {
            $nova_senha_hash = hashPassword($nova_senha);
            $stmt = $db->prepare("UPDATE administradores SET senha = ? WHERE ID = ?");
            if ($stmt->execute([$nova_senha_hash, $_SESSION['admin_id']])) {
                $message = 'Senha alterada com sucesso!';
                
                // Recarregar dados
                $stmt = $db->prepare("SELECT * FROM administradores WHERE ID = ?");
                $stmt->execute([$_SESSION['admin_id']]);
                $admin = $stmt->fetch();

                $stmt = $db->prepare("SELECT * FROM administradores WHERE ID = ?");
                $stmt->execute([$_SESSION['admin_id']]);
                $admin = $stmt->fetch(); 
            } else {
                $error = 'Erro ao alterar senha.';
            }
        }
    }
}
?>

<main class="main-content">
    <div class="admin-header">
        <h1 class="admin-title">Meu Perfil</h1>
        <a href="dashboard.php" class="btn btn-secondary">← Voltar</a>
    </div>
    
    <?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div class="profile-sections">
        <!-- Informações do Perfil -->
        <div class="profile-section">
            <h2 class="section-title">Informações do Perfil</h2>
            
            <div class="profile-info">
                <div class="profile-details">
                    <h3><?php echo htmlspecialchars($admin['nome'] ?? ''); ?></h3>
                    <div class="profile-meta">
                        <strong>Email:</strong> <?php echo htmlspecialchars($admin['email'] ?? ''); ?><br>
                        <strong>Cadastrado em:</strong> <?php echo formatDateTimeBR($admin['data_criacao'] ?? null); ?><br>
                        <strong>Último login:</strong> <?php echo formatDateTimeBR($admin['ultimo_login'] ?? null); ?>
                    </div>
                </div>
            </div><br>
            
            <form method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" id="nome" name="nome" class="form-input" 
                               value="<?php echo htmlspecialchars($admin['nome'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
            </form>
        </div><br><br>
        
        <!-- Alterar Senha -->
        <div class="profile-section">
            <h2 class="section-title">Alterar Senha</h2>
            
            <form method="POST">
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
        
        <!-- Estatísticas de Atividade -->
        <div class="profile-section">
            <h2 class="section-title">Suas Contribuições</h2>
            
            <?php
            // Buscar estatísticas do administrador
            $stmt = $db->prepare("SELECT COUNT(*) as TOTAL FROM eventos WHERE admin_id = ? AND ativo = 1");
            $stmt->execute([$_SESSION['admin_id']]);
            $eventos_criados = $stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT COUNT(*) as TOTAL FROM historias_sucesso WHERE admin_id = ? AND ativo = 1");
            $stmt->execute([$_SESSION['admin_id']]);
            $historias_criadas = $stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT COUNT(*) as TOTAL FROM galeria WHERE admin_id = ? AND ativo = 1");
            $stmt->execute([$_SESSION['admin_id']]);
            $fotos_adicionadas = $stmt->fetchColumn();
            ?>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $eventos_criados; ?></div>
                    <div class="stat-label">Eventos Criados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $historias_criadas; ?></div>
                    <div class="stat-label">Histórias Adicionadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $fotos_adicionadas; ?></div>
                    <div class="stat-label">Fotos na Galeria</div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once '../includes/admin_footer.php';
?>
