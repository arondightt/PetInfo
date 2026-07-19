<?php
declare(strict_types=1);

namespace App\Helpers;

class Hash
{
    // Gera ID alfanumérico de 12 chars para coleira (ex: "a3f9c2d17e4b")
    // Uso: Hash::coleiraId()
    public static function coleiraId(): string
    {
        return substr(bin2hex(random_bytes(8)), 0, 12);
    }

    // Valida se string é um ID de coleira válido
    // Uso: Hash::isColeiraId($id)
    public static function isColeiraId(string $id): bool
    {
        return (bool) preg_match('/^[a-f0-9]{12}$/', $id);
    }

    // Gera hash seguro de senha
    // Uso: Hash::senha('minha_senha')
    public static function senha(string $senha): string
    {
        return password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    // Verifica senha contra hash
    // Uso: Hash::verificarSenha('digitada', $hashDoBanco)
    public static function verificarSenha(string $senha, string $hash): bool
    {
        return password_verify($senha, $hash);
    }
}
