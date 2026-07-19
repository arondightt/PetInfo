<?php
declare(strict_types=1);

namespace App\Helpers;

class Validator
{
    // Sanitiza strings: remove espaços extras de todos os campos informados
    // Uso: Validator::trim($body, ['nome', 'email'])
    public static function trim(array &$data, array $campos): void
    {
        foreach ($campos as $campo) {
            if (isset($data[$campo]) && is_string($data[$campo])) {
                $data[$campo] = trim($data[$campo]);
            }
        }
    }

    // Verifica campos obrigatórios — retorna mensagem de erro ou null se ok
    // Uso: $erro = Validator::required($body, ['nome', 'email', 'senha'])
    public static function required(array $data, array $campos): ?string
    {
        foreach ($campos as $campo) {
            if (!isset($data[$campo]) || $data[$campo] === '') {
                return "Campo obrigatório ausente: {$campo}";
            }
        }
        return null;
    }

    // Valida formato de e-mail
    // Uso: Validator::email($body['email'])
    public static function email(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Valida WhatsApp: aceita +5511999999999 ou 11999999999 (10-15 dígitos)
    // Uso: Validator::whatsapp($body['whatsapp'])
    public static function whatsapp(string $phone): bool
    {
        $digits = preg_replace('/\D/', '', $phone);
        return strlen($digits) >= 10 && strlen($digits) <= 15;
    }

    // Valida handle do Instagram (com ou sem @, letras/números/._)
    // Uso: Validator::instagram($body['instagram'])
    public static function instagram(string $handle): bool
    {
        $handle = ltrim($handle, '@');
        return (bool) preg_match('/^[a-zA-Z0-9._]{1,30}$/', $handle);
    }

    // Valida tamanho máximo de string
    // Uso: Validator::maxLength($body['nome'], 100)
    public static function maxLength(string $value, int $max): bool
    {
        return mb_strlen($value) <= $max;
    }

    // Valida URL (foto, por exemplo)
    // Uso: Validator::url($body['foto_url'])
    public static function url(string $url): bool
    {
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }
}
