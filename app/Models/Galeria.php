<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Galeria extends Model
{
    protected static string $table = 'galeria';

    public static function ativas(string $order = 'data_criacao DESC'): array
    {
        return Database::query("SELECT * FROM galeria WHERE ativo = 1 ORDER BY {$order}")->fetchAll();
    }

    public static function findAtiva(int $id): array|false
    {
        return Database::query('SELECT * FROM galeria WHERE id = ? AND ativo = 1', [$id])->fetch();
    }

    public static function categorias(): array
    {
        $rows = Database::query(
            "SELECT DISTINCT categoria FROM galeria WHERE ativo = 1 AND categoria IS NOT NULL AND categoria <> '' ORDER BY categoria"
        )->fetchAll();
        return array_column($rows, 'categoria');
    }

    public static function create(array $data, int $adminId): int
    {
        Database::query(
            'INSERT INTO galeria (titulo, descricao, imagem, categoria, admin_id, ativo)
             VALUES (?, ?, ?, ?, ?, 1)',
            [
                $data['titulo'] ?? '',
                $data['descricao'] ?? '',
                $data['imagem'],
                $data['categoria'] ?? '',
                $adminId,
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        if (!empty($data['imagem'])) {
            return Database::query(
                'UPDATE galeria SET titulo = ?, descricao = ?, categoria = ?, imagem = ? WHERE id = ?',
                [$data['titulo'] ?? '', $data['descricao'] ?? '', $data['categoria'] ?? '', $data['imagem'], $id]
            )->rowCount() >= 0;
        }

        return Database::query(
            'UPDATE galeria SET titulo = ?, descricao = ?, categoria = ? WHERE id = ?',
            [$data['titulo'] ?? '', $data['descricao'] ?? '', $data['categoria'] ?? '', $id]
        )->rowCount() >= 0;
    }

    public static function softDelete(int $id): bool
    {
        return Database::query('UPDATE galeria SET ativo = 0 WHERE id = ?', [$id])->rowCount() > 0;
    }
}
