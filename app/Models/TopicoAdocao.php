<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class TopicoAdocao extends Model
{
    protected static string $table = 'topicos_adocao';

    public static function allOrdenados(): array
    {
        return Database::query('SELECT id, texto, ordem FROM topicos_adocao ORDER BY ordem ASC')->fetchAll();
    }

    public static function create(string $texto, int $ordem = 0): int
    {
        Database::query('INSERT INTO topicos_adocao (texto, ordem) VALUES (?, ?)', [$texto, $ordem]);
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, string $texto, int $ordem): bool
    {
        return Database::query(
            'UPDATE topicos_adocao SET texto = ?, ordem = ? WHERE id = ?',
            [$texto, $ordem, $id]
        )->rowCount() >= 0;
    }
}
