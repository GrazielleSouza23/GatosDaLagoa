<?php
/** @var array $configs */
/** @var string $error */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração <?php echo e($configs['site_titulo']['valor'] ?? SITE_NAME); ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/png" href="/assets/images/logo/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-page-container">
        <div class="login-image-panel">
            <div class="login-image-overlay"></div>
            <div class="login-image-content">
                <p>Cuidando dos gatos da Lagoa do Taquaral com amor e dedicação.</p>
                <a href="/" class="back-to-site-link">← Voltar ao site</a>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-container">
                <img src="/assets/images/logo/logocomnome.png" alt="Logo ONG" class="login-logo-img">
                <h1 class="login-title">Área Administrativa</h1>
                <p class="login-subtitle">Acesse para gerenciar o conteúdo do site.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo e($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/admin/login" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo old('email'); ?>" autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="password-wrapper">
                            <input type="password" id="senha" name="senha" class="form-control" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" id="togglePassword">👁️</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
