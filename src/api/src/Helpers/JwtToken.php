<?php
declare(strict_types=1);

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtToken
{
    private static string $algo = 'HS256';

    // Gera um token JWT com o payload informado
    // Uso: Jwt::generate(['tutor_id' => $id])
    public static function generate(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + (int) ($_ENV['JWT_EXPIRY'] ?? 604800); // 7 dias

        return JWT::encode($payload, $_ENV['JWT_SECRET'], self::$algo);
    }

    // Valida e decodifica um token JWT
    // Retorna o payload como array, ou false se inválido/expirado
    // Uso: $data = Jwt::validate($token)
    public static function validate(string $token): array|false
    {
        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], self::$algo));
            return (array) $decoded;
        } catch (Exception) {
            return false;
        }
    }
}
