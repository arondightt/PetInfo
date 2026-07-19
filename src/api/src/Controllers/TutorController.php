<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Tutor;
use App\Helpers\Response as Res;

class TutorController
{
    public function me(Request $request, Response $response): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $tutor   = Tutor::buscarAtivo($tutorId);

        if ($tutor === null) {
            return Res::error($response, 'Tutor não encontrado.', 404);
        }

        // Remove campos sensíveis antes de retornar
        unset($tutor['senha_hash']);

        return Res::success($response, $tutor);
    }

    public function atualizar(Request $request, Response $response): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $body    = $request->getParsedBody() ?? [];

        // Apenas campos permitidos para atualização
        $permitidos = ['nome', 'whatsapp', 'instagram'];
        $dados      = array_intersect_key($body, array_flip($permitidos));

        if (empty($dados)) {
            return Res::error($response, 'Nenhum campo válido para atualizar.', 422);
        }

        Tutor::update($tutorId, $dados);

        return Res::success($response, ['mensagem' => 'Perfil atualizado com sucesso.']);
    }
}
