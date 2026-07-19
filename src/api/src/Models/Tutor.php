<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Tutor extends BaseModel
{
    protected static string $table = 'tutores';

    // Busca tutor ativo por ID (inclui verificação ativo = TRUE)
    public static function buscarAtivo(string $id): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM tutores WHERE id = :id AND ativo = TRUE');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Busca tutor ativo por e-mail (usada no login)
    public static function buscarPorEmail(string $email): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM tutores WHERE email = :email AND ativo = TRUE');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    // Verifica se e-mail já está cadastrado
    public static function emailExiste(string $email): bool
    {
        return self::exists('email', $email);
    }

    // Soft delete — desativa o tutor sem apagar seus dados
    public static function desativar(string $id): void
    {
        self::update($id, ['ativo' => 'FALSE']);
    }
}
