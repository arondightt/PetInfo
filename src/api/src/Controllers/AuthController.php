<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Tutor;
use App\Helpers\Response as Res;
use App\Helpers\Hash;
use App\Helpers\JwtToken;

class AuthController
{
    public function cadastro(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody() ?? [];

        foreach (['nome', 'email', 'senha', 'whatsapp'] as $campo) {
            if (empty($body[$campo])) {
                return Res::error($response, "Campo obrigatório ausente: {$campo}", 422);
            }
        }

        if (Tutor::emailExiste($body['email'])) {
            return Res::error($response, 'E-mail já cadastrado.', 409);
        }

        $id = Tutor::create([
            'nome'       => $body['nome'],
            'email'      => $body['email'],
            'senha_hash' => Hash::senha($body['senha']),
            'whatsapp'   => $body['whatsapp'],
            'instagram'  => $body['instagram'] ?? null,
        ]);

        return Res::success($response, ['tutor_id' => $id], 201);
    }

    // Retorna JWT — o front guarda e envia nas requisições
    public function login(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody() ?? [];

        if (empty($body['email']) || empty($body['senha'])) {
            return Res::error($response, 'E-mail e senha são obrigatórios.', 422);
        }

        $tutor = Tutor::buscarPorEmail($body['email']);

        if ($tutor === null || !Hash::verificarSenha($body['senha'], $tutor['senha_hash'])) {
            return Res::error($response, 'Credenciais inválidas.', 401);
        }

        $token = JwtToken::generate(['tutor_id' => $tutor['id']]);

        return Res::success($response, [
            'token'    => $token,
            'tutor_id' => $tutor['id'],
            'nome'     => $tutor['nome'],
        ]);
    }

    // Logout apenas apaga o token no front (JWT é stateless)
    public function logout(Request $request, Response $response): Response
    {
        return Res::success($response, ['mensagem' => 'Logout realizado. Remova o token do cliente.']);
    }
}
