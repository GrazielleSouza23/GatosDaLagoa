-- ========================================
-- TABELA: administradores
-- Descrição: Armazena os usuários administradores do sistema
-- ========================================
CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME,
    ativo TINYINT(1) DEFAULT 1 CHECK (ativo IN (0,1)),
    INDEX idx_email (email),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: eventos
-- Descrição: Armazena os eventos da ONG
-- Relacionamento: FK com administradores
-- ========================================
CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_evento DATE NOT NULL,
    hora_evento VARCHAR(8),
    local_evento VARCHAR(255),
    imagem VARCHAR(255),
    ativo TINYINT(1) DEFAULT 1 CHECK (ativo IN (0,1)),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    admin_id INT,
    INDEX idx_data_evento (data_evento),
    INDEX idx_ativo (ativo),
    INDEX idx_admin_id (admin_id),
    CONSTRAINT fk_eventos_admin FOREIGN KEY (admin_id) 
        REFERENCES administradores(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: historias_sucesso
-- Descrição: Armazena histórias de adoção de gatos
-- Relacionamento: FK com administradores
-- ========================================
CREATE TABLE IF NOT EXISTS historias_sucesso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_gato VARCHAR(255) NOT NULL,
    idade VARCHAR(50),
    descricao TEXT,
    historia TEXT,
    imagem VARCHAR(255),
    data_adocao DATE,
    nome_adotante VARCHAR(255),
    ativo TINYINT(1) DEFAULT 1 CHECK (ativo IN (0,1)),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    admin_id INT,
    INDEX idx_nome_gato (nome_gato),
    INDEX idx_ativo (ativo),
    INDEX idx_admin_id (admin_id),
    CONSTRAINT fk_historias_admin FOREIGN KEY (admin_id) 
        REFERENCES administradores(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: galeria
-- Descrição: Armazena imagens da galeria da ONG
-- Relacionamento: FK com administradores
-- ========================================
CREATE TABLE IF NOT EXISTS galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descricao TEXT,
    imagem VARCHAR(255) NOT NULL,
    categoria VARCHAR(100),
    ativo TINYINT(1) DEFAULT 1 CHECK (ativo IN (0,1)),
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    admin_id INT,
    INDEX idx_categoria (categoria),
    INDEX idx_ativo (ativo),
    INDEX idx_admin_id (admin_id),
    CONSTRAINT fk_galeria_admin FOREIGN KEY (admin_id) 
        REFERENCES administradores(id) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: configuracoes
-- Descrição: Armazena configurações do sistema
-- ========================================
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(600) NOT NULL UNIQUE,
    valor TEXT,
    descricao TEXT,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA: topicos_adocao
-- Descrição: Armazena os tópicos da seção de adoção
-- ========================================
CREATE TABLE topicos_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto VARCHAR(255) NOT NULL,
    ordem INT DEFAULT 0
);

-- ========================================
-- TABELA: redes_sociais
-- Descrição: Armazena as redes sociais
-- ========================================
CREATE TABLE IF NOT EXISTS redes_sociais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(255) NOT NULL UNIQUE,      -- identificador único (ex: youtube, instagram, tiktok)
    icone VARCHAR(255) NOT NULL,             -- imagem do icone
    link VARCHAR(600) NOT NULL,               -- link da rede social
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_chave (chave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ========================================
-- INSERÇÃO DE DADOS INICIAIS
-- ========================================

INSERT IGNORE INTO administradores (email, senha, nome, data_criacao, ativo) VALUES (
    'email@exemplo.com',
    '$2y$10$quYnuFsL9wYydj39cyj2R..jSmR/4ehCVjxo39WRpHLtDwFCA5/Uu', /*Senha padrão: admin123*/
    'Administrador',
    CURRENT_TIMESTAMP,
    1
);

-- Configurações do sistema
INSERT IGNORE INTO configuracoes (chave, valor, descricao) VALUES 
    ('site_titulo', 'ONG - Gatos da Lagoa do Taquaral', 'Título principal do site'),
    ('site_descricao', 'Somos um grupo de cidadãos unidos pelo bem estar e cuidado dos gatos que vivem no Parque Portugal, em Campinas, SP. Juntos, alimentamos, castramos e promovemos adoções responsáveis.', 'Descrição principal do site'),
    ('email_contato', 'email@exemplo.com', 'Email de contato da ONG'),
    ('telefone_contato', '', 'Telefone de contato da ONG'),
    ('endereco', 'Parque Portugal - Campinas/SP', 'Endereço da ONG'),
    ('cnpj','21.657.568/0001-50','CNPJ da ONG'),
    ('titulo_pix','CNPJ:', 'Tipo de chave do PIX (CNPJ, Telefone, CPF, etc)'),
    ('pix', '21.657.568/0001-50', 'PIX para doações'),
    ('conta_banco', 'Agência: 3100 - Conta Corrente: 1453-9', 'Dados bancários para doações'),
    ('link_apoia_se', 'https://apoia.se/gatosdalagoataquaral', 'Link da campanha Apoia-se'),
    ('link_petlove', 'https://bit.ly/gatosdalagoa', 'Link da parceria Petlove'),
    ('link_tampinhas', '','Link da arrecadação'),
    ('titulo_missao','Nossa Missão','Título da Missão'),
    ('missao', 'Somos um grupo de cidadãos unidos pelo bem-estar e cuidado dos gatos que vivem no Parque Portugal, em Campinas, SP. Nosso compromisso é fornecer alimento e água fresca para mais de 200 gatos, além de promover o controle populacional por meio de castrações e incentivar adoções responsáveis.', 'Descrição da missão'),
    ('titulo_reconhecimento','Reconhecimento','Título do Reconhecimento'),
    ('reconhecimento', 'Fomos oficialmente declarados Órgão de Utilidade Pública Municipal, conforme o Projeto de Lei nº 81/19, Processo nº 229.479, de autoria do vereador Jorge Schneider. Este reconhecimento valida nosso trabalho dedicado aos felinos.', 'Descrição do Reconhecimento'),
    ('titulo_trabalho','Nosso Trabalho','Titulo do Trabalho'),
    ('trabalho','Todos os dias, reabastecemos os pontos de alimentação para garantir que esses animais tenham uma dieta saudável e balanceada, bem como água limpa e fresca. O trabalho no parque é contínuo e seguiremos nos dedicando para que cada um tenha uma vida digna.','Titulo do Trabalho'),
    ('titulo_atividades','Principais Atividades','Titulo Principais Atividades'),
    ('descricao_atividades','Nosso trabalho diário em prol dos gatinhos do Parque Portugal','Descrição das Atividades'),
    ('titulo_alimentadores_home','Alimentadores','Título dos Alimentadores'),
    ('descricao_alimentadores_home','Diariamente reabastecemos nossos 18 pontos de alimentação para garantir que todos esses bichinhos tenham uma dieta saudável e balanceada, além de água sempre limpa e fresca.','Descrição dos Alimentadores'),
    ('titulo_castracao','Castração','Título da Castração'),
    ('descricao_castracao','Nossa equipe de castração está sempre na ativa para a captura de gatos não castrados, que, caso forem ariscos, são devolvidos ao parque pelo programa CED (Captura - Esterilização - Devolução).','Descrição da Castração'),
    ('titulo_casinha_home','Casinha','Título da Casinha'),
    ('descricao_casinha_home','Os gatos mansos e filhotes são todos cuidados em nossa central de manejo - carinhosamente apelidada de Casinha e encaminhados para adoção responsável.','Descrição da Casinha'),
    ('titulo_historias','Histórias de Sucesso','Titulo das Historias'),
    ('descricao_historias','Conheça alguns dos nossos gatinhos que encontraram um lar feliz','Descrição das'),
    ('titulo_adocao','Adote um Gatinho','Titulo das Adoções'),
    ('descricao_adocao','Todos os nossos gatinhos são cuidados com muito carinho e estão prontos para encontrar um lar cheio de amor. Realizamos um processo de adoção responsável, com entrevista, visita e acompanhamento pós-adoção.','Descrição da Adoção'),
    ('link_formulario','https://forms.gle/Hk4rM6uCKXeZS4aD8','Link do Formulário de Adoção'),
    ('titulo_contato','Entre em Contato','Título da seção de Contato'),
    ('descricao_contato','Ficou com alguma dúvida ou quer saber mais sobre nosso trabalho? Entre em contato conosco!','Descrição da seção de Contato'),
    ('link_google_maps','https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3676.062505975364!2d-47.05998232469055!3d-22.87414927927807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjLCsDUyJzI2LjkiUyA0N8KwMDMnMjYuNyJX!5e0!3m2!1spt-BR!2sbr!4v1758228558670!5m2!1spt-BR!2sbr" width="100%" height="400" style="border:0; border-radius: 20px;" allowfullscreen="" loading="lazy" title="Mapa do Gatos da Lagoa Taquaral','Link da Localização do Google Maps'),
    ('titulo_voluntariado','Formas de Voluntariado','Titulo do Voluntariado'),
    ('descricao_voluntariado','Sua ajuda é fundamental para continuarmos nosso trabalho.','Descrição do Voluntariado'),
    ('titulo_casinha','Casinha','Titulo da Casinha'),
    ('descricao_casinha','A Casinha abriga filhotes e suas mães, gatos machucados ou em recuperação. Exige limpeza, cuidados frequentes, experiência com gatos, saber medicar e não ter medo. Precisa estar com as vacinas em dia.','Descrição'),
    ('titulo_alimentadores','Alimentadores','Titulo dos Alimentadores'),
    ('descricao_alimentadores','O voluntário deve ir nos dias quentes ou frios, sob sol ou chuva, limpar e abastecer os comedouros com ração e água fresca. É preciso estar atento para reportar animais machucados.','Descrição'),
    ('titulo_como_voluntariar','Como se Voluntariar','Titulo do Como se voluntariar'),
    ('descricao_como_voluntariar','Após se comprometer, o voluntário entra em uma escala e passa por uma experiência de no mínimo 3 dias.','Descrição'),
    ('titulo_doacoes','Campanhas e Doações','Título da seção de Doações'),
    ('descricao_doacoes','Sua doação é 100% revertida para o cuidado dos nossos gatinhos. Veja como ajudar:','Descrição da seção de Doações'),
    ('titulo_apoia_se','Apoia-se','Título do Apoia-se'),
    ('descricao_apoia_se','Participe da nossa campanha de arrecadação mensal e ajude de forma recorrente.','Descrição do Apoia-se'),
    ('titulo_outras_formas', 'Outras Formas de Ajudar', 'Título da seção Outras Formas de Ajudar'),
    ('titulo_tampinhas', 'Tampinhas e Lacres', 'Título da seção de Tampinhas e Lacres'),
    ('descricao_tampinhas', 'Arrecadação de tampinhas plásticas e lacres de latinha, que são enviados para reciclagem e convertidos em renda. Temos um ponto de coleta fixo no Portão 3 da GM.', 'Descrição da seção de Tampinhas e Lacres'),
    ('titulo_petlove', 'Parceria Petlove', 'Título da seção de Parceria Petlove'),
    ('descricao_petlove', 'Temos uma parceria com a Petlove: ao comprar pelo nosso link, 10% do valor da venda é revertido para a ONG.', 'Descrição da seção de Parceria Petlove'),
    ('titulo_feiras', 'Feiras e Eventos', 'Título da seção de Feiras e Eventos'),
    ('descricao_feiras', 'Marcamos presença em feiras e eventos de Campinas, vendendo produtos artesanais. 100% da renda arrecadada é revertida para a ONG.','Descrição da seção de Feiras e Eventos'),

    ('voluntariado_interesse','Interessados, enviem um email para:', 'Texto escrito acima do e-mail de contato na página Voluntariado'),
    ('imagem_inicial', 'Gato_Inicial.png', 'Imagem da seção inicial (Home)'),
    ('imagem_adote', 'Adote_Gatinho.jpg', 'Imagem da seção Adote um Gatinho'),
    ('imagem_missao', 'Gato1.jpg', 'Imagem da seção Missão'),
    ('imagem_reconhecimento', 'Gato2.jpg', 'Imagem da seção Reconhecimento'),
    ('imagem_trabalho', 'Gato3.jpeg', 'Imagem da seção Nosso Trabalho');


INSERT INTO topicos_adocao (texto, ordem) VALUES
('Gatos castrados', 1),
('Acompanhamento pós-adoção', 2),
('Termo de responsabilidade', 3);

INSERT INTO redes_sociais (chave, icone, link) VALUES
    ('instagram', 'instagram.png', 'https://instagram.com/gatosdalagoa'),
    ('facebook', 'facebook.png', 'https://facebook.com/GatosDaLagoaTaquaral');

-- Eventos de exemplo
INSERT INTO eventos (titulo, descricao, data_evento, hora_evento, local_evento, admin_id) VALUES 
    ('Feira de Adoção', 
     'Feira com produtos como panos decorativos, camisetas exclusivas e cadernos personalizados. Cada compra ajuda nosso projeto de adoção de gatinhos.', 
     '2025-05-17', 
     '09:00', 
     'Concha Acústica do Taquaral', 
     1),
    ('11ª Mostra Jazz Campinas', 
     'A ONG Gatos da Lagoa estará presente na 11ª Mostra Jazz Campinas, com música, solidariedade e artesanato. Evento gratuito e cheio de energia contagiante!', 
     '2025-08-10', 
     '15:00', 
     'Concha Acústica do Taquaral - Parque Portugal', 
     1),
    ('+1 Baile 0800 na Concha', 
     'Evento gratuito com música, cultura de rua e artesanato. A ONG Gatos da Lagoa estará presente com produtos especiais para apoiar os gatinhos do Parque Taquaral.', 
     '2025-08-31', 
     '14:00', 
     'Concha Acústica do Taquaral', 
     1);

-- Histórias de sucesso de exemplo
INSERT INTO historias_sucesso (nome_gato, descricao, imagem, admin_id)
VALUES 
    ('Guida', 
     'Guida foi encontrada com tumor em toda a arcada mamária. Guida passou por cirurgia de retirada do tumor e foi adotada em 2024. Hoje está bem gordinha e foi descoberto um câncer.',
     'Guida.jpg', 
     1),
    ('Vivara', 
     'Vivara (à direita), encontrada grávida, hoje está adotada e tem um irmão preto também.',
     'Vivara e irmao.jpg', 
     1),
    ('Casal de irmãos',  
     'Casal de irmãos encontrados com rinotraqueite, hoje estão adotados.',
     'Casal de irmaos.jpg', 
     1);

-- Mensagem de confirmação
SELECT 'Tabelas criadas e dados iniciais inseridos com sucesso!' AS Mensagem;