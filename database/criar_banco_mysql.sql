-- ========================================
-- Script de Criação do Banco de Dados
-- Projeto: ONG Gatos da Lagoa Taquaral
-- Tecnologia: MySQL 8.0
-- ========================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS ong_gatos_taquaral 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE ong_gatos_taquaral;


-- Crie o usuário manualmente no ambiente de instalação
-- usando uma senha segura e específica para o ambiente.

-- Exemplo:
--CREATE USER IF NOT EXISTS 'gatos'@'localhost' IDENTIFIED BY 'SENHA_SEGURA_AQUI'; --Essas credenciais são apenas de EXEMPLO


-- Dar permissões completas no banco
GRANT ALL PRIVILEGES ON ong_gatos_taquaral.* TO 'gatos'@'localhost';

-- Mensagem de confirmação
SELECT 'Banco de dados criado com sucesso!' AS Mensagem;