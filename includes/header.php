
<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'config.php';

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


$stmt = $db->query("SELECT id, chave, icone, link FROM redes_sociais");
$redes = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $db->prepare("SELECT id, nome_gato, SUBSTRING(descricao, 1, 4000) AS descricao,
    SUBSTRING(historia, 1, 4000) AS historia,
    imagem, nome_adotante, idade, data_adocao
FROM historias_sucesso
WHERE ativo = 1
ORDER BY data_adocao DESC
LIMIT 3");

$stmt->execute();
$historias = $stmt->fetchAll();

foreach ($historias as $i => $historia) {
    foreach ($historia as $key => $value) {
        $historias[$i][$key] = $value;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($configs["site_descricao"]["valor"] ?? ''); ?>">
    <title><?php echo htmlspecialchars($configs["site_titulo"]["valor"] ?? ''); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/font.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/logo.png">
</head>
<body>
    <!-- Header com navegação -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="../index.php">
                    <img src="../assets/images/logo/logocomnome.png" alt="Logo ONG Gatos da Lagoa Taquaral">
                </a>
            </div>
            <nav class="nav-menu" id="navMenu">
                <ul class="nav-list">
                    <li><a href="../index.php" class="nav-link"><img src="../assets/images/icones/home.png" alt="ícone"></a></li>
                    <li><a href="QuemSomos.php" class="nav-link">Quem Somos</a></li>
                    <li><a href="../index.php#adocao" class="nav-link">Adoção</a></li>
                    <li><a href="Eventos.php" class="nav-link">Eventos</a></li>
                    <li><a href="Voluntariado.php" class="nav-link">Voluntariado</a></li>
                    <li><a href="Doacoes.php" class="nav-link">Doações</a></li>
                    <li><a href="Galeria.php" class="nav-link">Galeria</a></li>
                    <li><a href="Contato.php" class="nav-link">Contato</a></li>
                    <li><a href="../admin/login.php" class="nav-link"><img src="../assets/images/icones/user.png" alt="ícone"></a></li>
                </ul>
            </nav>
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

