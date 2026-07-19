<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

class Historico extends BaseModel
{
    protected static string $table = 'historico_localizacoes';

    // Conta registros de hoje para a coleira — usado no rate limiting (máx 20/dia)
    public static function contarHoje(string $coleiraId): int
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT COUNT(*)
            FROM historico_localizacoes
            WHERE coleira_id = :coleira_id
              AND data_hora >= CURRENT_DATE
        ');
        $stmt->execute([':coleira_id' => $coleiraId]);
        return (int) $stmt->fetchColumn();
    }

    // Registra localização enviada pelo front (após consentimento do usuário)
    public static function registrar(array $dados): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            INSERT INTO historico_localizacoes
                (coleira_id, cidade, estado, bairro, latitude, longitude, precisao)
            VALUES
                (:coleira_id, :cidade, :estado, :bairro, :latitude, :longitude, :precisao)
        ');
        $stmt->execute($dados);
    }

    // Lista histórico de uma coleira, mais recentes primeiro, limitado a 100
    public static function listarPorColeira(string $coleiraId): array
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare('
            SELECT id, cidade, estado, bairro, latitude, longitude, precisao, data_hora
            FROM historico_localizacoes
            WHERE coleira_id = :coleira_id
            ORDER BY data_hora DESC
            LIMIT 100
        ');
        $stmt->execute([':coleira_id' => $coleiraId]);
        return $stmt->fetchAll();
    }

    // Apaga todo o histórico de uma coleira (direito de exclusão — LGPD)
    public static function limpar(string $coleiraId): void
    {
        $pdo = Database::getInstance();
        $pdo->prepare('DELETE FROM historico_localizacoes WHERE coleira_id = :coleira_id')
            ->execute([':coleira_id' => $coleiraId]);
    }
}
