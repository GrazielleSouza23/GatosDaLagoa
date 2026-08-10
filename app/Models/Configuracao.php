<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Configuracao extends Model
{
    protected static string $table = 'configuracoes';

    /** Todas as configurações, indexadas por chave (formato usado pelas Views). */
    public static function allAsMap(): array
    {
        $rows = Database::query('SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave')->fetchAll();

        $configs = [];
        foreach ($rows as $row) {
            $configs[$row['chave']] = [
                'valor' => $row['valor'] ?? '',
                'descricao' => $row['descricao'] ?? '',
                'atualizacao' => $row['data_atualizacao'],
            ];
        }
        return $configs;
    }

    /** Lista "crua", no formato usado pela API. */
    public static function allList(): array
    {
        return Database::query('SELECT id, chave, valor, descricao, data_atualizacao FROM configuracoes ORDER BY chave')->fetchAll();
    }

    public static function findByChave(string $chave): array|false
    {
        return Database::query('SELECT * FROM configuracoes WHERE chave = ?', [$chave])->fetch();
    }

    public static function get(string $chave, string $default = ''): string
    {
        $row = self::findByChave($chave);
        return $row ? ($row['valor'] ?? $default) : $default;
    }

    public static function set(string $chave, string $valor, ?string $descricao = null): bool
    {
        if (self::findByChave($chave)) {
            if ($descricao === null) {
                return Database::query(
                    'UPDATE configuracoes SET valor = ? WHERE chave = ?',
                    [$valor, $chave]
                )->rowCount() >= 0;
            }
            return Database::query(
                'UPDATE configuracoes SET valor = ?, descricao = ? WHERE chave = ?',
                [$valor, $descricao, $chave]
            )->rowCount() >= 0;
        }

        return Database::query(
            'INSERT INTO configuracoes (chave, valor, descricao) VALUES (?, ?, ?)',
            [$chave, $valor, $descricao ?? '']
        )->rowCount() > 0;
    }

    public static function setMany(array $pares): void
    {
        foreach ($pares as $chave => $valor) {
            self::set($chave, (string) $valor);
        }
    }
}
