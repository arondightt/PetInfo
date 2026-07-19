<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
                    $_ENV['DB_HOST'],
                    $_ENV['DB_PORT']  ?? '5432',
                    $_ENV['DB_NAME'],
                    $_ENV['DB_SSL']   ?? 'require'
                );

                self::$connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

            } catch (PDOException $e) {
                $msg = $_ENV['APP_ENV'] === 'development'
                    ? $e->getMessage()
                    : 'Erro de conexão com o banco de dados';

                http_response_code(500);
                echo json_encode(['success' => false, 'error' => $msg]);
                exit;
            }
        }

        return self::$connection;
    }

    private function __construct() {}
    private function __clone() {}
}
