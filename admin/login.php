<?php
require_once __DIR__ . 
'/../includes/config.php';

// Se já estiver logado, redirecionar para dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

// Buscar configurações do site
$db = getDatabase();

$stmt = $db->query("SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave");

$configs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $chave = $row["chave"];

    $valor = $row["valor"];
    $descricao = $row["descricao"];

    $configs[$chave] = [
        "valor" => $valor ?? "",
        "descricao" => $descricao ?? "",
        "atualizacao" => $row["data_atualizacao"],
    ];
}

$error = "";

if ($_POST) {
    $email = sanitize($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (empty($email) || empty($senha)) {
        $error = "Por favor, preencha todos os campos.";
    } elseif (!validateEmail($email)) {
        $error = "Email inválido.";
    } else {
        $db = getDatabase();
        $stmt = $db->prepare(
            "SELECT id, email, senha, nome FROM administradores WHERE email = ? AND ativo = 1"
        );
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $error = "Admin não encontrado ou inativo.";
        } else {
            // Exemplo debug senha armazenada
            // echo 'Senha do banco: ' . $admin['SENHA'];

            if (verifyPassword($senha, $admin["senha"])) {
                session_regenerate_id(true);
                // Login bem-sucedido
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_email"] = $admin["email"];
                $_SESSION["admin_nome"] = $admin["nome"];

                // Atualizar último login
                $stmt = $db->prepare(
                    "UPDATE administradores SET ultimo_login = CURRENT_TIMESTAMP WHERE id = ?"
                );
                $stmt->execute([$admin["id"]]);

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Email ou senha incorretos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração <?php echo htmlspecialchars($configs["site_titulo"]["valor"] ?? ""); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-page-container">
        <div class="login-image-panel">
            <div class="login-image-overlay"></div>
            <div class="login-image-content">
                <p>Cuidando dos gatos da Lagoa do Taquaral com amor e dedicação.</p>
                <a href="../index.php" class="back-to-site-link">← Voltar ao site</a>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-container">
                <img src="../assets/images/logo/logocomnome.png" alt="Logo ONG" class="login-logo-img">
                <h1 class="login-title">Área Administrativa</h1>
                <p class="login-subtitle">Acesse para gerenciar o conteúdo do site.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="login-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="password-wrapper">
                            <input type="password" id="senha" name="senha" class="form-control" required>
                            <button type="button" class="password-toggle" id="togglePassword">👁️</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Entrar</button>

                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>