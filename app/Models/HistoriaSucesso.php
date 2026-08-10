<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class HistoriaSucesso extends Model
{
    protected static string $table = 'historias_sucesso';

    public static function recentes(int $limit = 3): array
    {
        return Database::query(
            "SELECT id, nome_gato, SUBSTRING(descricao, 1, 4000) AS descricao,
                    SUBSTRING(historia, 1, 4000) AS historia,
                    imagem, nome_adotante, idade, data_adocao
             FROM historias_sucesso
             WHERE ativo = 1
             ORDER BY data_criacao DESC
             LIMIT " . (int) $limit
        )->fetchAll();
    }

    public static function ativas(string $order = 'data_criacao DESC'): array
    {
        return Database::query("SELECT * FROM historias_sucesso WHERE ativo = 1 ORDER BY {$order}")->fetchAll();
    }

    public static function findAtiva(int $id): array|false
    {
        return Database::query('SELECT * FROM historias_sucesso WHERE id = ? AND ativo = 1', [$id])->fetch();
    }

    public static function create(array $data, int $adminId): int
    {
        Database::query(
            'INSERT INTO historias_sucesso (nome_gato, idade, descricao, historia, imagem, data_adocao, nome_adotante, admin_id, ativo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)',
            [
                $data['nome_gato'],
                $data['idade'] ?? '',
                $data['descricao'] ?? '',
                $data['historia'] ?? '',
                $data['imagem'] ?? null,
                $data['data_adocao'] ?: null,
                $data['nome_adotante'] ?? '',
                $adminId,
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        if (!empty($data['imagem'])) {
            return Database::query(
                'UPDATE historias_sucesso SET nome_gato = ?, idade = ?, descricao = ?, historia = ?, imagem = ?, data_adocao = ?, nome_adotante = ? WHERE id = ?',
                [$data['nome_gato'], $data['idade'] ?? '', $data['descricao'] ?? '', $data['historia'] ?? '', $data['imagem'], $data['data_adocao'] ?: null, $data['nome_adotante'] ?? '', $id]
            )->rowCount() >= 0;
        }

        return Database::query(
            'UPDATE historias_sucesso SET nome_gato = ?, idade = ?, descricao = ?, historia = ?, data_adocao = ?, nome_adotante = ? WHERE id = ?',
            [$data['nome_gato'], $data['idade'] ?? '', $data['descricao'] ?? '', $data['historia'] ?? '', $data['data_adocao'] ?: null, $data['nome_adotante'] ?? '', $id]
        )->rowCount() >= 0;
    }

    public static function removeImagem(int $id): void
    {
        Database::query('UPDATE historias_sucesso SET imagem = NULL WHERE id = ?', [$id]);
    }

    public static function softDelete(int $id): bool
    {
        return Database::query('UPDATE historias_sucesso SET ativo = 0 WHERE id = ?', [$id])->rowCount() > 0;
    }
}
