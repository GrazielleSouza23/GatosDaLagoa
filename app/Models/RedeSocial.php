<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class RedeSocial extends Model
{
    protected static string $table = 'redes_sociais';

    public static function all(string $orderBy = 'chave'): array
    {
        return Database::query('SELECT id, chave, icone, link, data_atualizacao FROM redes_sociais ORDER BY ' . $orderBy)->fetchAll();
    }

    public static function create(array $data): int
    {
        Database::query(
            'INSERT INTO redes_sociais (chave, icone, link) VALUES (?, ?, ?)',
            [$data['chave'], $data['icone'], $data['link']]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        return Database::query(
            'UPDATE redes_sociais SET chave = ?, icone = ?, link = ? WHERE id = ?',
            [$data['chave'], $data['icone'], $data['link'], $id]
        )->rowCount() >= 0;
    }
}
