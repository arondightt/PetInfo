<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Coleira;
use App\Helpers\Response as Res;

class ColeiraController
{
    public function listar(Request $request, Response $response): Response
    {
        $tutorId  = $request->getAttribute('tutor_id');
        $coleiras = Coleira::listarPorTutor($tutorId);

        return Res::success($response, $coleiras);
    }

    // Gera novo ID único e insere na tabela
    public function gerar(Request $request, Response $response): Response
    {
        $id = Coleira::gerar();

        return Res::success($response, ['id' => $id], 201);
    }

    // Associa coleira a um pet
    public function vincular(Request $request, Response $response, array $args): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $body    = $request->getParsedBody() ?? [];

        if (empty($body['pet_id'])) {
            return Res::error($response, 'O campo pet_id é obrigatório.', 422);
        }

        $ok = Coleira::vincular($args['id'], $body['pet_id'], $tutorId);

        if (!$ok) {
            return Res::error($response, 'Coleira não encontrada ou não pertence a você.', 404);
        }

        return Res::success($response, ['mensagem' => 'Coleira vinculada com sucesso.']);
    }

    public function desvincular(Request $request, Response $response, array $args): Response
    {
        Coleira::desvincular($args['id']);

        return Res::success($response, ['mensagem' => 'Coleira desvinculada.']);
    }

    // Desativa (soft delete)
    public function deletar(Request $request, Response $response, array $args): Response
    {
        Coleira::desativar($args['id']);

        return Res::success($response, ['mensagem' => 'Coleira desativada.']);
    }
}
