<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Pet extends BaseModel
{
    protected static string $table = 'pets';

    // Lista todos os pets ativos de um tutor
    public static function listarPorTutor(string $tutorId): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT id, nome, especie, raca, idade, recompensa, foto_url, observacoes, criado_em
            FROM pets
            WHERE tutor_id = :tutor_id
              AND ativo = TRUE
            ORDER BY criado_em DESC
        ');
        $stmt->execute([':tutor_id' => $tutorId]);
        return $stmt->fetchAll();
    }

    // Busca pet garantindo que pertence ao tutor — evita acesso cruzado entre contas
    public static function buscarDoTutor(string $id, string $tutorId): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT * FROM pets
            WHERE id = :id
              AND tutor_id = :tutor_id
              AND ativo = TRUE
        ');
        $stmt->execute([':id' => $id, ':tutor_id' => $tutorId]);
        return $stmt->fetch() ?: null;
    }

    // Soft delete — mantém o histórico de localizações da coleira vinculada
    public static function desativar(string $id): void
    {
        self::update($id, ['ativo' => 'FALSE']);
    }
}
