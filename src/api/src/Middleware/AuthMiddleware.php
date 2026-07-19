<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;
use App\Helpers\JwtToken;
use App\Helpers\Response as Res;

class AuthMiddleware implements MiddlewareInterface
{
    // Valida JWT via header Authorization: Bearer <token>
    // A arquitetura é decoupled (front e API em domínios diferentes), logo usamos apenas JWT.
    public function process(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            $response = new SlimResponse();
            return Res::error($response, 'Token não informado. Faça login para continuar.', 401);
        }

        $token   = substr($authHeader, 7);
        $payload = JwtToken::validate($token);

        if ($payload === false) {
            $response = new SlimResponse();
            return Res::error($response, 'Token inválido ou expirado. Faça login novamente.', 401);
        }

        // Injeta o tutor_id no request — controllers acessam via $request->getAttribute('tutor_id')
        $request = $request->withAttribute('tutor_id', $payload['tutor_id']);

        return $handler->handle($request);
    }
}
