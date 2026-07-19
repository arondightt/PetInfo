<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

abstract class BaseModel
{
    protected static string $table      = '';
    protected static string $primaryKey = 'id';

    public static function find(string $id): ?array
    {
        $pdo  = Database::getInstance();
        $sql  = 'SELECT * FROM ' . static::$table
              . ' WHERE ' . static::$primaryKey . ' = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findBy(string $coluna, mixed $valor): ?array
    {
        $pdo  = Database::getInstance();
        $sql  = "SELECT * FROM " . static::$table . " WHERE {$coluna} = :valor LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':valor' => $valor]);
        return $stmt->fetch() ?: null;
    }

    public static function findAllBy(string $coluna, mixed $valor): array
    {
        $pdo  = Database::getInstance();
        $sql  = "SELECT * FROM " . static::$table . " WHERE {$coluna} = :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':valor' => $valor]);
        return $stmt->fetchAll();
    }

    public static function create(array $dados): string
    {
        $pdo     = Database::getInstance();
        $colunas = implode(', ', array_keys($dados));
        $params  = implode(', ', array_map(fn($k) => ":{$k}", array_keys($dados)));
        $sql     = "INSERT INTO " . static::$table . " ({$colunas}) VALUES ({$params})"
                 . " RETURNING " . static::$primaryKey;
        $stmt    = $pdo->prepare($sql);
        $stmt->execute($dados);
        return (string) $stmt->fetchColumn();
    }

    public static function update(string $id, array $dados): void
    {
        $pdo    = Database::getInstance();
        $campos = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($dados)));
        $sql    = "UPDATE " . static::$table . " SET {$campos}"
                . " WHERE " . static::$primaryKey . " = :pkval";
        $stmt   = $pdo->prepare($sql);
        $stmt->execute([...$dados, 'pkval' => $id]);
    }

    public static function hardDelete(string $id): void
    {
        $pdo  = Database::getInstance();
        $sql  = "DELETE FROM " . static::$table
              . " WHERE " . static::$primaryKey . " = :id";
        $pdo->prepare($sql)->execute([':id' => $id]);
    }

    public static function exists(string $coluna, mixed $valor): bool
    {
        return self::findBy($coluna, $valor) !== null;
    }
}
