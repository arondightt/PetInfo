<?php
declare(strict_types=1);

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;

class Response
{
    public static function success(ResponseInterface $res, mixed $data, int $status = 200): ResponseInterface
    {
        return self::json($res, ['success' => true, 'data' => $data], $status);
    }

    public static function error(ResponseInterface $res, string $message, int $status = 400): ResponseInterface
    {
        return self::json($res, ['success' => false, 'error' => $message], $status);
    }

    public static function json(ResponseInterface $res, array $data, int $status = 200): ResponseInterface
    {
        $res->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $res
            ->withHeader('Content-Type', 'application/json; charset=UTF-8')
            ->withStatus($status);
    }
}
