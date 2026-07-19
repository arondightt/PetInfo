<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use App\Helpers\Hash;

class Coleira extends BaseModel
{
    protected static string $table      = 'coleiras';
    protected static string $primaryKey = 'id'; // VARCHAR(12), não UUID

    // Busca perfil público completo — a query mais importante do sistema.
    // Usa nomes completos de tabela (sem aliases curtos) para legibilidade.
    // Retorna null se: coleira inexistente, inativa, sem pet vinculado,
    // pet inativo ou tutor inativo. O controller retorna 404 em todos esses casos.
    public static function buscarPerfil(string $id): ?array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT
                pets.nome,
                pets.especie,
                pets.raca,
                pets.idade,
                pets.recompensa,
                pets.foto_url,
                pets.observacoes,
                tutores.nome AS tutor_nome,
                tutores.whatsapp,
                tutores.instagram
            FROM coleiras
            INNER JOIN pets
                ON pets.id = coleiras.pet_id
            INNER JOIN tutores
                ON tutores.id = pets.tutor_id
            WHERE coleiras.id     = :id
              AND coleiras.status = \'ativa\'
              AND pets.ativo      = TRUE
              AND tutores.ativo   = TRUE
        ');
        $stmt->execute([':id' => $id]);
        $perfil = $stmt->fetch();

        if (!$perfil) {
            return null;
        }

        // Busca última localização
        $stmtLoc = $pdo->prepare('
            SELECT cidade, estado, bairro, precisao, data_hora
            FROM historico_localizacoes
            WHERE coleira_id = :id
            ORDER BY data_hora DESC
            LIMIT 1
        ');
        $stmtLoc->execute([':id' => $id]);
        $perfil['ultima_localizacao'] = $stmtLoc->fetch() ?: null;

        return $perfil;
    }

    // Lista coleiras associadas ao tutor (diretamente via pets)
    public static function listarPorTutor(string $tutorId): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT
                coleiras.id,
                coleiras.status,
                coleiras.criado_em,
                pets.nome AS pet_nome
            FROM coleiras
            LEFT JOIN pets
                ON pets.id = coleiras.pet_id
            WHERE pets.tutor_id = :tutor_id
               OR coleiras.pet_id IS NULL
            ORDER BY coleiras.criado_em DESC
        ');
        $stmt->execute([':tutor_id' => $tutorId]);
        return $stmt->fetchAll();
    }

    // Gera ID único com verificação de colisão antes de inserir
    public static function gerar(): string
    {
        $pdo = Database::getInstance();
        do {
            $id   = Hash::coleiraId();
            $stmt = $pdo->prepare('SELECT id FROM coleiras WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } while ($stmt->fetch());

        $pdo->prepare('INSERT INTO coleiras (id) VALUES (:id)')
            ->execute([':id' => $id]);

        return $id;
    }

    // Vincula coleira a um pet, validando que o pet pertence ao tutor
    public static function vincular(string $id, string $petId, string $tutorId): bool
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            UPDATE coleiras
            SET pet_id = :pet_id
            WHERE coleiras.id     = :id
              AND coleiras.status = \'ativa\'
              AND EXISTS (
                  SELECT 1 FROM pets
                  WHERE pets.id       = :pet_id
                    AND pets.tutor_id = :tutor_id
              )
        ');
        $stmt->execute([
            ':pet_id'   => $petId,
            ':id'       => $id,
            ':tutor_id' => $tutorId,
        ]);
        return $stmt->rowCount() > 0;
    }

    // Desvincula o pet da coleira (pet_id volta para NULL)
    public static function desvincular(string $id): void
    {
        $pdo = Database::getInstance();
        $pdo->prepare('UPDATE coleiras SET pet_id = NULL WHERE id = :id')
            ->execute([':id' => $id]);
    }

    // Soft delete — muda status para inativa
    public static function desativar(string $id): void
    {
        self::update($id, ['status' => 'inativa']);
    }
}
