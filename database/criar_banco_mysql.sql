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

-- Criar usuário (caso não exista)
CREATE USER IF NOT EXISTS 'gatos'@'localhost' IDENTIFIED BY 'SENHA_AQUI';

-- Dar permissões completas no banco
GRANT ALL PRIVILEGES ON ong_gatos_taquaral.* TO 'gatos'@'localhost';

-- Mensagem de confirmação
SELECT 'Banco de dados criado com sucesso!' AS Mensagem;