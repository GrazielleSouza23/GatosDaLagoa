<?php
$is_404 = true;
// Inclui o cabeçalho para manter a consistência visual
require_once 'includes/header.php';
?>

<main class="main-content error-page">
    <section class="error-section">
        <div class="container">
            <div class="error-content">
                <h1 class="error-code">404</h1>
                <h2 class="error-title">Página Não Encontrada</h2>
                <p class="error-message">Ops! Parece que a página que você está procurando não existe ou foi movida.</p>
                <p class="error-suggestion">Não se preocupe, você pode voltar para a página inicial</p>
                <a href="/GatosDaLagoa/index.php" class="btn btn-primary">Voltar para a Página Inicial</a>
            </div>
        </div>
    </section>
</main>

<?php
// Inclui o rodapé
require_once 'includes/footer.php';
?>