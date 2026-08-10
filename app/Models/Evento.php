<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Evento extends Model
{
    protected static string $table = 'eventos';

    public static function ativos(string $order = 'data_evento DESC'): array
    {
        return Database::query("SELECT * FROM eventos WHERE ativo = 1 ORDER BY {$order}")->fetchAll();
    }

    public static function proximos(int $limit = 5): array
    {
        return Database::query(
            'SELECT * FROM eventos WHERE ativo = 1 AND data_evento >= CURDATE() ORDER BY data_evento ASC LIMIT ' . (int) $limit
        )->fetchAll();
    }

    public static function futuros(): array
    {
        return Database::query(
            'SELECT * FROM eventos WHERE ativo = 1 AND data_evento >= CURDATE() ORDER BY data_evento ASC'
        )->fetchAll();
    }

    public static function passados(): array
    {
        return Database::query(
            'SELECT * FROM eventos WHERE ativo = 1 AND data_evento < CURDATE() ORDER BY data_evento DESC'
        )->fetchAll();
    }

    public static function findAtiva(int $id): array|false
    {
        return Database::query('SELECT * FROM eventos WHERE id = ? AND ativo = 1', [$id])->fetch();
    }

    public static function create(array $data, int $adminId): int
    {
        Database::query(
            'INSERT INTO eventos (titulo, descricao, data_evento, hora_evento, local_evento, imagem, admin_id, ativo)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1)',
            [
                $data['titulo'],
                $data['descricao'] ?? '',
                $data['data_evento'],
                $data['hora_evento'] ?? null,
                $data['local_evento'] ?? '',
                $data['imagem'] ?? null,
                $adminId,
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        if (!empty($data['imagem'])) {
            return Database::query(
                'UPDATE eventos SET titulo = ?, descricao = ?, data_evento = ?, hora_evento = ?, local_evento = ?, imagem = ? WHERE id = ?',
                [$data['titulo'], $data['descricao'] ?? '', $data['data_evento'], $data['hora_evento'] ?? null, $data['local_evento'] ?? '', $data['imagem'], $id]
            )->rowCount() >= 0;
        }

        return Database::query(
            'UPDATE eventos SET titulo = ?, descricao = ?, data_evento = ?, hora_evento = ?, local_evento = ? WHERE id = ?',
            [$data['titulo'], $data['descricao'] ?? '', $data['data_evento'], $data['hora_evento'] ?? null, $data['local_evento'] ?? '', $id]
        )->rowCount() >= 0;
    }

    public static function removeImagem(int $id): void
    {
        Database::query('UPDATE eventos SET imagem = NULL WHERE id = ?', [$id]);
    }

    /** Soft delete, igual ao comportamento original. */
    public static function softDelete(int $id): bool
    {
        return Database::query('UPDATE eventos SET ativo = 0 WHERE id = ?', [$id])->rowCount() > 0;
    }
}
