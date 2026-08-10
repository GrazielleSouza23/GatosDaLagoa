<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Administrador extends Model
{
    protected static string $table = 'administradores';

    public static function findByEmail(string $email): array|false
    {
        return Database::query(
            'SELECT id, email, senha, nome FROM administradores WHERE email = ? AND ativo = 1',
            [$email]
        )->fetch();
    }



    public static function findById(int $id): array|false
    {
        $resultado = Database::query(
            'SELECT id, email, nome, data_criacao, ultimo_login FROM administradores WHERE id = ? AND ativo = 1',
            [$id]
        )->fetch();

        return $resultado;
    }



    public static function touchLastLogin(int $id): void
    {
        Database::query('UPDATE administradores SET ultimo_login = CURRENT_TIMESTAMP WHERE id = ?', [$id]);
    }

    public static function updatePerfil(int $id, string $nome, string $email): bool
    {
        return Database::query(
            'UPDATE administradores SET nome = ?, email = ? WHERE id = ?',
            [$nome, $email, $id]
        )->rowCount() >= 0;
    }

    public static function updateSenha(int $id, string $novaSenhaHash): bool
    {
        return Database::query(
            'UPDATE administradores SET senha = ? WHERE id = ?',
            [$novaSenhaHash, $id]
        )->rowCount() >= 0;
    }

    public static function senhaAtualHash(int $id): ?string
    {
        $row = Database::query('SELECT senha FROM administradores WHERE id = ?', [$id])->fetch();
        return $row['senha'] ?? null;
    }
}
