<?php
require_once '../includes/admin_header.php';

$db = getDatabase();

// Arrays para mensagens
$messages = [];
$success_flag = false; // Flag para rastrear se alguma operação de sucesso ocorreu
$errors = [];

// Função para pegar caminho da imagem
function getImagePath($config, $default) {
    if (is_string($config)) $config = ['valor' => $config];
    if (!is_array($config) || empty($config['valor'])) return '../assets/images/' . $default;

    $filename = htmlspecialchars($config['valor']);
    $uploadRel = '../assets/images/uploads/' . $filename;
    $uploadAbs = realpath(__DIR__ . '/../assets/images/uploads') . '/' . $filename;

    if ($uploadAbs && file_exists($uploadAbs)) return $uploadRel;

    return '../assets/images/' . $default;
}

// Diretório de uploads de ícones
$uploadDirIcons = "../assets/images/icones/";
if (!is_dir($uploadDirIcons)) mkdir($uploadDirIcons, 0775, true); // Garantir que o diretório existe

// ======================
// PROCESSAMENTO DO FORMULÁRIO
// ======================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Deletar rede social: apenas se delete_rede for definido e não vazio
    if (!empty($_POST['delete_rede'])) {
        $id = intval($_POST['delete_rede']);
        $stmt = $db->prepare("SELECT icone FROM redes_sociais WHERE id = ?");
        $stmt->execute([$id]);
        $rede = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rede) {
            $iconePath = $uploadDirIcons . $rede['icone'];
            // Verifica se o arquivo existe e não é o default.png antes de deletar
            if (file_exists($iconePath) && $rede['icone'] !== 'default.png') unlink($iconePath);

            $stmtDel = $db->prepare("DELETE FROM redes_sociais WHERE id = ?");
            if ($stmtDel->execute([$id])) {
                $success_flag = true;
            } else {
                $errors[] = 'Erro ao remover a rede social.';
            }
        } else {
            $errors[] = 'Rede social não encontrada.';
        }
    }

    // Adicionar nova rede social: apenas se nova_chave e nova_link forem preenchidos
    if (!empty($_POST['nova_chave']) && !empty($_POST['nova_link'])) {
        $chave = strtolower(trim($_POST['nova_chave']));
        $link  = trim($_POST['nova_link']);
        $icone = "default.png";

        // Upload do ícone
        if (!empty($_FILES['nova_icone']['name'])) {
            $ext = pathinfo($_FILES['nova_icone']['name'], PATHINFO_EXTENSION);
            $icone = $chave . '_' . time() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['nova_icone']['tmp_name'], $uploadDirIcons . $icone)) {
                // $messages[] = "Ícone da nova rede enviado com sucesso!"; // Mensagem consolidada no final
            } else {
                $errors[] = "Falha ao enviar o ícone da nova rede.";
                $icone = "default.png";
            }
        }

        $stmtCheck = $db->prepare("SELECT COUNT(*) FROM redes_sociais WHERE chave = ?");
        $stmtCheck->execute([$chave]);
        if ($stmtCheck->fetchColumn() > 0) {
            $errors[] = "Erro: A rede social '{$chave}' já existe. Escolha um nome diferente.";
        } else {
            $stmt = $db->prepare("INSERT INTO redes_sociais (chave, icone, link) VALUES (?, ?, ?)");
            if ($stmt->execute([$chave, $icone, $link])) {
                $success_flag = true;
            } else {
                $errors[] = "Erro ao adicionar a rede social.";
            }
        }

    }
    
    // ======================
    // ATUALIZAÇÃO DE REDES SOCIAIS EXISTENTES
    // ======================
    $redes_atualizadas = 0;
    foreach ($_POST as $key => $value) {
        // Verifica se a chave corresponde ao padrão 'link_{id}'
        if (preg_match('/^link_(\d+)$/', $key, $matches)) {
            $id = $matches[1];
            $link = trim($value);
            
            // Atualiza o link
            $stmt = $db->prepare("UPDATE redes_sociais SET link = ? WHERE id = ?");
            if ($stmt->execute([$link, $id])) {
                $redes_atualizadas++;
            } else {
                $errors[] = "Erro ao atualizar o link da rede social ID $id.";
            }
            
            // Verifica se há um novo ícone para esta rede
            $icone_key = "icone_$id";
            if (isset($_FILES[$icone_key]) && $_FILES[$icone_key]['error'] === UPLOAD_ERR_OK) {
                
                // 1. Busca o ícone antigo para deletar
                $stmt = $db->prepare("SELECT icone, chave FROM redes_sociais WHERE id = ?");
                $stmt->execute([$id]);
                $rede = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($rede) {
                    // Deleta o ícone antigo, se não for o default.png
                    $iconeAntigoPath = $uploadDirIcons . $rede['icone'];
                    if (file_exists($iconeAntigoPath) && $rede['icone'] !== 'default.png') {
                        unlink($iconeAntigoPath);
                    }
                    
                    // 2. Faz o upload do novo ícone
                    $ext = pathinfo($_FILES[$icone_key]['name'], PATHINFO_EXTENSION);
                    $novoIcone = $rede['chave'] . '_' . time() . '.' . $ext;
                    
                    if (move_uploaded_file($_FILES[$icone_key]['tmp_name'], $uploadDirIcons . $novoIcone)) {
                        // 3. Atualiza o nome do ícone no banco de dados
                        $stmt = $db->prepare("UPDATE redes_sociais SET icone = ? WHERE id = ?");
                        if ($stmt->execute([$novoIcone, $id])) {
                            // $messages[] = "Ícone da rede social '{$rede['chave']}' atualizado com sucesso!"; // Mensagem consolidada no final
                        } else {
                            $errors[] = "Erro ao salvar o novo ícone da rede social '{$rede['chave']}' no banco.";
                        }
                    } else {
                        $errors[] = "Falha ao enviar o novo ícone para a rede social '{$rede['chave']}'.";
                    }
                }
            }
        }
    }
    
    if ($redes_atualizadas > 0) $success_flag = true;


    // ======================
    // UPLOAD DE IMAGENS DO SITE
    // ======================
    $imagens = [
        'imagem_inicial', 'imagem_adote', 'imagem_missao',
        'imagem_reconhecimento', 'imagem_trabalho'
    ];

    $uploadDirImgs = __DIR__ . '/../assets/images/uploads/';
    if (!is_dir($uploadDirImgs)) mkdir($uploadDirImgs, 0775, true);

    foreach ($imagens as $campo) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION);
            $nomeArquivo = $campo . '_' . time() . '.' . $ext;
            $destino = $uploadDirImgs . $nomeArquivo;

            $tipo = mime_content_type($_FILES[$campo]['tmp_name']);
            if (strpos($tipo, 'image/') !== 0) {
                $errors[] = "O arquivo enviado para <b>$campo</b> não é uma imagem válida.";
                continue;
            }

            if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
                $stmt = $db->prepare("UPDATE configuracoes SET valor = ?, data_atualizacao = SYSDATE() WHERE chave = ?");
                if ($stmt->execute([$nomeArquivo, $campo])) {
                    // $messages[] = "Imagem <b>$campo</b> atualizada com sucesso!"; // Mensagem consolidada no final
                } else {
                    $errors[] = "Erro ao salvar imagem <b>$campo</b> no banco.";
                }
            } else {
                $errors[] = "Falha ao mover o arquivo <b>$campo</b>.";
            }
        }
    }

    // ======================
    // Atualizar configurações textuais
    // ======================

    $configs_to_update = [ 
        'site_titulo','site_descricao','titulo_atividades','descricao_atividades',
        'titulo_alimentadores_home','descricao_alimentadores_home','titulo_castracao','descricao_castracao',
        'titulo_casinha_home','descricao_casinha_home','titulo_adocao','descricao_adocao','link_formulario',
        'titulo_historias','descricao_historias','titulo_missao','missao','titulo_reconhecimento','reconhecimento',
        'titulo_trabalho','trabalho','titulo_contato','descricao_contato','email_contato','telefone_contato','endereco',
        'link_google_maps','cnpj','titulo_voluntariado','descricao_voluntariado','titulo_casinha','descricao_casinha',
        'titulo_alimentadores','descricao_alimentadores','titulo_como_voluntariar','descricao_como_voluntariar',
        'voluntariado_interesse','titulo_doacoes','descricao_doacoes','titulo_pix','pix','conta_banco',
        'titulo_apoia_se','descricao_apoia_se','link_apoia_se','titulo_outras_formas','titulo_tampinhas',
        'descricao_tampinhas','link_tampinhas','titulo_petlove','descricao_petlove','link_petlove',
        'titulo_feiras','descricao_feiras'
    ];

    $updated = 0;
    foreach ($configs_to_update as $config) {
        if (isset($_POST[$config])) {
            $valor = sanitize($_POST[$config]);
            $stmt = $db->prepare("UPDATE configuracoes SET valor = ?, data_atualizacao = SYSDATE() WHERE chave = ?");
            if ($stmt->execute([$valor, $config])) $updated++;
        }
    }

    if ($updated > 0) $success_flag = true;

    // ======================
    // Atualizar tópicos de adoção
    // ======================
    if (isset($_POST['topicos']) && is_array($_POST['topicos'])) {
        try {
            $db->beginTransaction();
            $db->exec("DELETE FROM topicos_adocao");

            $ordem = 1;
            foreach ($_POST['topicos'] as $texto) {
                $texto = trim($texto);
                if ($texto !== '') {
                    $stmt = $db->prepare("INSERT INTO topicos_adocao (texto, ordem) VALUES (?, ?)");
                    $stmt->execute([sanitize($texto), $ordem++]);
                }
            }

            $db->commit();
            $success_flag = true;
        } catch (PDOException $e) {
            $db->rollBack();
            $errors[] = 'Erro ao atualizar tópicos: ' . $e->getMessage();
        }
    }
    
    // Adiciona a mensagem de sucesso consolidada no final
    if ($success_flag && empty($errors)) {
        $messages[] = 'Configurações atualizadas com sucesso!';
    }
}

// ======================
// BUSCAR CONFIGURAÇÕES E REDES
// ======================
$stmt = $db->query("SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave");
$configs = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $configs[$row['chave']] = [
        'valor' => is_resource($row['valor']) ? stream_get_contents($row['valor']) : $row['valor'],
        'descricao' => is_resource($row['descricao']) ? stream_get_contents($row['descricao']) : $row['descricao'],
        'atualizacao' => $row['data_atualizacao']
    ];
}

$lista_redes = $db->query("SELECT * FROM redes_sociais ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Tópicos de adoção
$topicos = $db->query("SELECT id, texto, ordem FROM topicos_adocao ORDER BY ordem ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<main class="main-content">
    <div class="admin-header">
        <h1 class="admin-title">Configurações do Site</h1>
    </div>

    <!-- Mensagens -->
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message success"><?php echo $msg; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <div class="message error"><?php echo $err; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="config-sections">

            <!-- Informações Gerais -->
            <div class="config-section">
                <h2 class="section-title">Informações Gerais</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="site_titulo" class="form-label">Título do Site</label>
                        <input type="text" id="site_titulo" name="site_titulo" class="form-input" value="<?php echo htmlspecialchars($configs['site_titulo']['valor'] ?? ''); ?>">
                        <div class="help-text">Título que aparece na aba do navegador e no cabeçalho</div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="site_descricao" class="form-label">Descrição do Site</label>
                        <textarea id="site_descricao" name="site_descricao" class="form-textarea"><?php echo htmlspecialchars($configs['site_descricao']['valor'] ?? ''); ?></textarea>
                        <div class="help-text">Descrição principal que aparece na página inicial</div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Imagem Inicial:</label>
                        <input type="file" name="imagem_inicial" accept="image/*" class="form-input">
                        <img src="<?php echo getImagePath($configs['imagem_inicial'], 'Gato_Inicial.png') . '?v=' . time(); ?>"
                            alt="Imagem Inicial" class="preview-image">
                    </div>

                </div>
            </div>

            <br><br><br>

            <!-- Atividades -->
            <div class="config-section">
                <h2 class="section-title">Principais Atividades</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="titulo_atividades" class="form-label">Título das Atividades</label>
                        <input type="text" id="titulo_atividades" name="titulo_atividades" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_atividades']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="descricao_atividades" class="form-label">Descrição das Atividades</label>
                        <textarea id="descricao_atividades" name="descricao_atividades" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_atividades']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="titulo_alimentadores_home" class="form-label">Título dos Alimentadores</label>
                        <input type="text" id="titulo_alimentadores_home" name="titulo_alimentadores_home" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_alimentadores_home']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="descricao_alimentadores_home" class="form-label">Descrição dos Alimentadores</label>
                        <textarea id="descricao_alimentadores_home" name="descricao_alimentadores_home" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_alimentadores_home']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="titulo_castracao" class="form-label">Título da seção de Castração:</label>
                        <input type="text" id="titulo_castracao" name="titulo_castracao" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_castracao']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="descricao_castracao" class="form-label">Descrição da seção de Castração:</label>
                        <textarea id="descricao_castracao" name="descricao_castracao" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_castracao']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="titulo_casinha_home" class="form-label">Título da Casinha</label>
                        <input type="text" id="titulo_casinha_home" name="titulo_casinha_home" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_casinha_home']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="descricao_casinha_home" class="form-label">Descrição da Casinha</label>
                        <textarea id="descricao_casinha_home" name="descricao_casinha_home" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_casinha_home']['valor'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
            
            <br><br><br>

            <!-- Adoções de Gatos -->
                        <div>
                <h2 class="section-title">Adoção de Gatos</h2>
                <div class="form-group full-width">
                        <label for="titulo_adocao" class="form-label">Título da Seção de Adoção:</label>
                        <input type="text" id="titulo_adocao" name="titulo_adocao" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_adocao']['valor'] ?? ''); ?>">
                </div>

                <div class="form-group full-width">
                        <label for="descricao_adocao" class="form-label">Descrição da Seção de Adoção:</label>
                        <textarea id="descricao_adocao" name="descricao_adocao" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_adocao']['valor'] ?? ''); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Imagem da Seção de Adoção:</label>
                    <input type="file" name="imagem_adote" accept="image/*" class="form-input">
                    <img src="<?php echo getImagePath($configs['imagem_adote'], 'Adote_Gatinho.jpg') . '?v=' . time(); ?>"
                        alt="Imagem da seção Adoção" class="preview-image">
                </div>

                <div id="lista-topicos">
                        <?php if (!empty($topicos)): ?>
                            <?php foreach ($topicos as $i => $topico): ?>
                                <div class="form-group">
                                    <label class="form-label">Tópico de Adoção <?php echo $i + 1; ?></label>
                                    <input type="text" name="topicos[]" class="form-input"
                                        value="<?php echo htmlspecialchars($topico['texto']); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="form-group">
                                <label class="form-label">Tópico 1</label>
                                <input type="text" name="topicos[]" class="form-input" placeholder="Digite o texto do tópico">
                            </div>
                        <?php endif; ?>
                </div>

                <div class="form-actions" style="margin-top: 10px;">
                        <button type="button" id="btnAddTopico" class="btn btn-secondary">+ Adicionar Tópico</button>
                        <button type="button" id="btnRemoveTopico" class="btn btn-danger" style="margin-top: 10px;">– Remover Último</button>
                </div>
            </div>
            <br>
            <div class="form-group full-width">
                <label for="link_formulario" class="form-label">Link do Formulário (Seção de Adoção):</label>
                <input type="text" id="link_formulario" name="link_formulario" class="form-input"
                    value="<?php echo htmlspecialchars($configs['link_formulario']['valor'] ?? ''); ?>">
            </div>
            <br><br><br>

            <!-- Histórias de Sucesso -->
            <div class="config-section">
                <h2 class="section-title">Histórias de Sucesso</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="titulo_historias" class="form-label">Título da Seção de Histórias:</label>
                        <input type="text" id="titulo_historias" name="titulo_historias" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_historias']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_historias" class="form-label">Descrição da Seção de Histórias:</label>
                        <textarea id="descricao_historias" name="descricao_historias" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_historias']['valor'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

            <br><br><br>

            <!-- QUEM SOMOS - Missão, Reconhecimento e Nosso Trabalho -->
            <div class="config-section">
                <h2 class="section-title">Quem Somos</h2>
                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="titulo_missao" class="form-label">Título da Missão</label>
                        <input type="text" id="titulo_missao" name="titulo_missao" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_missao']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="missao" class="form-label">Descrição da Missão</label>
                        <textarea id="missao" name="missao" class="form-textarea"><?php echo htmlspecialchars($configs['missao']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Imagem da Seção Missão:</label>
                        <input type="file" name="imagem_missao" accept="image/*" class="form-input">
                        <img src="<?php echo getImagePath($configs['imagem_missao'], 'Gato1.jpg') . '?v=' . time(); ?>"
                            alt="Imagem da seção Missão" class="preview-image">
                    </div>

                    <div class="form-group full-width">
                        <label for="titulo_reconhecimento" class="form-label">Título de Reconhecimento</label>
                        <input type="text" id="titulo_reconhecimento" name="titulo_reconhecimento" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_reconhecimento']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="reconhecimento" class="form-label">Descrição de Reconhecimento</label>
                        <textarea id="reconhecimento" name="reconhecimento" class="form-textarea"><?php echo htmlspecialchars($configs['reconhecimento']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Imagem da Seção Reconhecimento:</label>
                        <input type="file" name="imagem_reconhecimento" accept="image/*" class="form-input">
                        <img src="<?php echo getImagePath($configs['imagem_reconhecimento'], 'gato2.jpg') . '?v=' . time(); ?>"
                            alt="Imagem da seção Reconhecimento" class="preview-image">
                    </div>

                    <div class="form-group full-width">
                        <label for="titulo_trabalho" class="form-label">Título "Nosso Trabalho"</label>
                        <input type="text" id="titulo_trabalho" name="titulo_trabalho" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_trabalho']['valor'] ?? ''); ?>">
                    </div>
                    <div class="form-group full-width">
                        <label for="trabalho" class="form-label">Descrição "Nosso Trabalho"</label>
                        <textarea id="trabalho" name="trabalho" class="form-textarea"><?php echo htmlspecialchars($configs['trabalho']['valor'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Imagem da Seção Nosso Trabalho:</label>
                        <input type="file" name="imagem_trabalho" accept="image/*" class="form-input">
                        <img src="<?php echo getImagePath($configs['imagem_trabalho'], 'Gato3.jpeg') . '?v=' . time(); ?>"
                            alt="Imagem da seção Nosso Trabalho" class="preview-image">
                    </div>

                </div>
            </div>

            <br><br><br>

            <!-- Voluntariado -->
            <div class="config-section">
                <h2 class="section-title">Informações de Voluntariado</h2>
                <div class="form-grid">

                    <!-- Título e Descrição da Seção -->
                    <div class="form-group full-width">
                        <label for="titulo_voluntariado" class="form-label">Título da Seção de Voluntariado</label>
                        <input type="text" id="titulo_voluntariado" name="titulo_voluntariado" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_voluntariado']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_voluntariado" class="form-label">Descrição da Seção de Voluntariado</label>
                        <textarea id="descricao_voluntariado" name="descricao_voluntariado" class="form-input" rows="4"><?php echo htmlspecialchars($configs['descricao_voluntariado']['valor'] ?? ''); ?></textarea>
                    </div>

                    <!-- Casinha -->
                    <div class="form-group full-width">
                        <label for="titulo_casinha" class="form-label">Título da Seção "Casinha"</label>
                        <input type="text" id="titulo_casinha" name="titulo_casinha" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_casinha']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_casinha" class="form-label">Descrição da Seção "Casinha"</label>
                        <textarea id="descricao_casinha" name="descricao_casinha" class="form-input" rows="4"><?php echo htmlspecialchars($configs['descricao_casinha']['valor'] ?? ''); ?></textarea>
                    </div>

                    <!-- Alimentadores -->
                    <div class="form-group full-width">
                        <label for="titulo_alimentadores" class="form-label">Título da Seção "Alimentadores"</label>
                        <input type="text" id="titulo_alimentadores" name="titulo_alimentadores" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_alimentadores']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_alimentadores" class="form-label">Descrição da Seção "Alimentadores"</label>
                        <textarea id="descricao_alimentadores" name="descricao_alimentadores" class="form-input" rows="4"><?php echo htmlspecialchars($configs['descricao_alimentadores']['valor'] ?? ''); ?></textarea>
                    </div>

                    <!-- Como se Voluntariar -->
                    <div class="form-group full-width">
                        <label for="titulo_como_voluntariar" class="form-label">Título da Seção "Como se Voluntariar"</label>
                        <input type="text" id="titulo_como_voluntariar" name="titulo_como_voluntariar" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_como_voluntariar']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_como_voluntariar" class="form-label">Descrição da Seção "Como se Voluntariar"</label>
                        <textarea id="descricao_como_voluntariar" name="descricao_como_voluntariar" class="form-input" rows="4"><?php echo htmlspecialchars($configs['descricao_como_voluntariar']['valor'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="voluntariado_interesse" class="form-label">Interessados, enviem um email para: </label>
                        <input type="text" id="voluntariado_interesse" name="voluntariado_interesse" class="form-input"
                            value="<?php echo htmlspecialchars($configs['voluntariado_interesse']['valor'] ?? ''); ?>">
                    </div>

                </div>
            </div>

            <br><br><br>

            <!-- Doações -->
            <div class="config-section">
                <h2 class="section-title">Informações para Doações</h2>
                <div class="form-grid">

                    <!-- Título da seção de Doações -->
                    <div class="form-group">
                        <label for="titulo_doacoes" class="form-label">Título da Seção de Doações</label>
                        <input type="text" id="titulo_doacoes" name="titulo_doacoes" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_doacoes']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Descrição da seção de Doações -->
                    <div class="form-group">
                        <label for="descricao_doacoes" class="form-label">Descrição da Seção de Doações</label>
                        <textarea id="descricao_doacoes" name="descricao_doacoes" class="form-input" rows="4"><?php 
                            echo htmlspecialchars($configs['descricao_doacoes']['valor'] ?? ''); 
                        ?></textarea>
                    </div>

                    <!-- Título do PIX -->
                    <div class="form-group">
                        <label for="titulo_pix" class="form-label">Título do PIX</label>
                        <input type="text" id="titulo_pix" name="titulo_pix" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_pix']['valor'] ?? ''); ?>">
                        <div class="help-text">Tipo de chave do Pix: CPF, CNPJ, Telefone, E-Mail, etc</div>
                    </div>

                    <div class="form-group">
                        <label for="pix" class="form-label">PIX </label>
                        <input type="text" id="pix" name="pix" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['pix']['valor'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="conta_banco" class="form-label">Dados Bancários</label>
                        <input type="text" id="conta_banco" name="conta_banco" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['conta_banco']['valor'] ?? ''); ?>">
                        <div class="help-text">Ex: Agência: 3100 - Conta Corrente: 1453-9</div>
                    </div>
                    
                    <!-- Título Apoia-se -->
                    <div class="form-group">
                        <label for="titulo_apoia_se" class="form-label">Título do Apoia-se</label>
                        <input type="text" id="titulo_apoia_se" name="titulo_apoia_se" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_apoia_se']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Descrição Apoia-se -->
                    <div class="form-group">
                        <label for="descricao_apoia_se" class="form-label">Descrição do Apoia-se</label>
                        <textarea id="descricao_apoia_se" name="descricao_apoia_se" class="form-input" rows="4"><?php 
                            echo htmlspecialchars($configs['descricao_apoia_se']['valor'] ?? ''); 
                        ?></textarea>
                    </div>

                    <!-- Link Apoia-se -->
                    <div class="form-group">
                        <label for="link_apoia_se" class="form-label">Link Apoia-se</label>
                        <input type="url" id="link_apoia_se" name="link_apoia_se" class="form-input"
                            value="<?php echo htmlspecialchars($configs['link_apoia_se']['valor'] ?? ''); ?>">
                        <?php if (!empty($configs['link_apoia_se']['valor'])): ?>
                            <a href="<?php echo htmlspecialchars($configs['link_apoia_se']['valor']); ?>" target="_blank" class="preview-link">
                                Ver página →
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Título da seção "Outras Formas de Ajudar" -->
                    <div class="form-group">
                        <label for="titulo_outras_formas" class="form-label">Título da Seção "Outras Formas de Ajudar"</label>
                        <input type="text" id="titulo_outras_formas" name="titulo_outras_formas" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_outras_formas']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Título da campanha de tampinhas -->
                    <div class="form-group">
                        <label for="titulo_tampinhas" class="form-label">Título da Campanha de Tampinhas</label>
                        <input type="text" id="titulo_tampinhas" name="titulo_tampinhas" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_tampinhas']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Descrição da campanha de tampinhas -->
                    <div class="form-group">
                        <label for="descricao_tampinhas" class="form-label">Descrição da Campanha de Tampinhas</label>
                        <textarea id="descricao_tampinhas" name="descricao_tampinhas" class="form-input" rows="4"><?php 
                            echo htmlspecialchars($configs['descricao_tampinhas']['valor'] ?? ''); 
                        ?></textarea>
                    </div>

                    <!-- Link da campanha de tampinhas -->
                    <div class="form-group">
                        <label for="link_tampinhas" class="form-label">Link da Campanha de Tampinhas</label>
                        <input type="text" id="link_tampinhas" name="link_tampinhas" class="form-input"
                            value="<?php echo htmlspecialchars($configs['link_tampinhas']['valor'] ?? ''); ?>">
                    </div>


                    <!-- Título Petlove -->
                    <div class="form-group">
                        <label for="titulo_petlove" class="form-label">Título da Seção Petlove</label>
                        <input type="text" id="titulo_petlove" name="titulo_petlove" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_petlove']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Descrição Petlove -->
                    <div class="form-group">
                        <label for="descricao_petlove" class="form-label">Descrição da Seção Petlove</label>
                        <textarea id="descricao_petlove" name="descricao_petlove" class="form-input" rows="4"><?php 
                            echo htmlspecialchars($configs['descricao_petlove']['valor'] ?? ''); 
                        ?></textarea>
                    </div>

                    <!-- Link Petlove -->
                    <div class="form-group">
                        <label for="link_petlove" class="form-label">Link Petlove</label>
                        <input type="url" id="link_petlove" name="link_petlove" class="form-input"
                            value="<?php echo htmlspecialchars($configs['link_petlove']['valor'] ?? ''); ?>">
                        <?php if (!empty($configs['link_petlove']['valor'])): ?>
                            <a href="<?php echo htmlspecialchars($configs['link_petlove']['valor']); ?>" target="_blank" class="preview-link">
                                Ver página →
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Título Feiras -->
                    <div class="form-group">
                        <label for="titulo_feiras" class="form-label">Título da Seção de Feiras</label>
                        <input type="text" id="titulo_feiras" name="titulo_feiras" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_feiras']['valor'] ?? ''); ?>">
                    </div>

                    <!-- Descrição Feiras -->
                    <div class="form-group">
                        <label for="descricao_feiras" class="form-label">Descrição da Seção de Feiras</label>
                        <textarea id="descricao_feiras" name="descricao_feiras" class="form-input" rows="4"><?php 
                            echo htmlspecialchars($configs['descricao_feiras']['valor'] ?? ''); 
                        ?></textarea>
                    </div>
                </div>
            </div>

            <br><br><br><br>

            <!-- Contato -->
            <div class="config-section">
                <h2 class="section-title">Informações de Contato</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="titulo_contato" class="form-label">Título da Seção de Contato</label>
                        <input type="text" id="titulo_contato" name="titulo_contato" class="form-input"
                            value="<?php echo htmlspecialchars($configs['titulo_contato']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao_contato" class="form-label">Descrição do Contato</label>
                        <textarea id="descricao_contato" name="descricao_contato" class="form-textarea"><?php echo htmlspecialchars($configs['descricao_contato']['valor'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email_contato" class="form-label">Email de Contato</label>
                        <input type="email" id="email_contato" name="email_contato" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['email_contato']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="telefone_contato" class="form-label">Telefone de Contato</label>
                        <input type="text" id="telefone_contato" name="telefone_contato" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['telefone_contato']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="endereco" class="form-label">Localização (Endereço)</label>
                        <input type="text" id="endereco" name="endereco" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['endereco']['valor'] ?? ''); ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="cnpj" class="form-label">CNPJ: </label>
                        <input type="text" id="cnpj" name="cnpj" class="form-input" value="<?php echo htmlspecialchars($configs['cnpj']['valor'] ?? ''); ?>">
                        <div class="help-text">Atualiza o CNPJ do site inteiro, que inclusive está no footer do site. </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="link_google_maps" class="form-label">Link do Google Maps</label>
                        <input type="url" id="link_google_maps" name="link_google_maps" class="form-input" 
                            value="<?php echo htmlspecialchars($configs['link_google_maps']['valor'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <br><br><br>
            
        <!-- Redes Sociais -->
        <div class="config-section">
            <h2 class="section-title">Redes Sociais</h2>

            <div class="form-grid">
                <?php foreach ($lista_redes as $rede): ?>
                    <div class="form-group" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        <label class="form-label" style="font-weight: bold; font-size: 1.1em;"><?php echo ucfirst($rede['chave']); ?></label>

                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                            <!-- Ícone Atual -->
                            <img src="<?php echo $uploadDirIcons . htmlspecialchars($rede['icone']); ?>" 
                                alt="Ícone <?php echo $rede['chave']; ?>" 
                                style="width:40px; height:40px; border-radius:6px; object-fit: cover;">

                            <!-- Link -->
                            <input type="url" 
                                name="link_<?php echo $rede['id']; ?>" 
                                class="form-input"
                                value="<?php echo htmlspecialchars($rede['link']); ?>"
                                placeholder="Link completo da rede social"
                                style="flex:1;">
                        </div>

                        <!-- Upload de novo ícone -->
                        <label class="form-label" style="margin-top:8px;">Alterar ícone:
                            <input type="file" name="icone_<?php echo $rede['id']; ?>" class="form-input">
                        </label>
                        
                        <!-- Botão de Remoção (usa JavaScript para enviar o ID para o formulário principal) -->
                        <button type="button" 
                                class="btn btn-danger btn-small" 
                                style="margin-top: 10px;"
                                onclick="if(confirm('Tem certeza que quer remover a rede social <?php echo $rede['chave']; ?>?')) { 
                                    document.getElementById('delete_rede_id').value = '<?php echo $rede['id']; ?>'; 
                                    this.closest('form').submit(); 
                                }">
                            Remover rede social
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="config-section">
            <h2 class="section-title">Adicionar Nova Rede Social</h2>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nome da rede (ex: Instagram, Tiktok, Youtube)</label>
                    <input type="text" name="nova_chave" class="form-input" placeholder="Ex: tiktok">
                </div>

                <div class="form-group">
                    <label class="form-label">Link completo</label>
                    <input type="url" name="nova_link" class="form-input" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label class="form-label">Ícone (png, jpg) </label>
                    <input type="file" name="nova_icone" class="form-input">
                </div>
            </div>
        </div>

        <input type="hidden" name="delete_rede" id="delete_rede_id" value="">
        
        <div style="margin-top: 30px; text-align: center;">
            <button type="submit" class="btn btn-primary">Salvar Configurações</button>
        </div>
    </form>
</main>

<?php
require_once '../includes/admin_footer.php';
?>
