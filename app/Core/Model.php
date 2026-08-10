<?php

namespace App\Core;

/**
 * Model base: fornece acesso ao banco para todos os Models do domínio.
 * Cada Model concreto (Evento, Galeria, HistoriaSucesso, ...) define sua
 * própria tabela e métodos específicos, reaproveitando estes utilitários.
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    public static function all(string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM ' . static::$table;
        if ($orderBy) {
            $sql .= ' ORDER BY ' . $orderBy;
        }
        return Database::query($sql)->fetchAll();
    }

    public static function find(int $id): array|false
    {
        return Database::query(
            'SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?',
            [$id]
        )->fetch();
    }

    public static function delete(int $id): bool
    {
        return Database::query(
            'DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?',
            [$id]
        )->rowCount() > 0;
    }
}
