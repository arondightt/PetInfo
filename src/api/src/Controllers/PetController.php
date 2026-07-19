<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Pet;
use App\Helpers\Response as Res;

class PetController
{
    public function listar(Request $request, Response $response): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $pets    = Pet::listarPorTutor($tutorId);

        return Res::success($response, $pets);
    }

    public function detalhe(Request $request, Response $response, array $args): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $pet     = Pet::buscarDoTutor($args['id'], $tutorId);

        if ($pet === null) {
            return Res::error($response, 'Pet não encontrado.', 404);
        }

        return Res::success($response, $pet);
    }

    public function criar(Request $request, Response $response): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $body    = $request->getParsedBody() ?? [];

        if (empty($body['nome'])) {
            return Res::error($response, 'O nome do pet é obrigatório.', 422);
        }

        $id = Pet::create([
            'tutor_id'    => $tutorId,
            'nome'        => $body['nome'],
            'especie'         => $body['especie']         ?? null,
            'raca'            => $body['raca']            ?? null,
            'data_nascimento' => $body['data_nascimento'] ?? null,
            'recompensa'      => $body['recompensa']      ?? null,
            'observacoes'     => $body['observacoes']     ?? null,
            'foto_url'        => $body['foto_url']        ?? null,
        ]);

        return Res::success($response, ['id' => $id], 201);
    }

    public function atualizar(Request $request, Response $response, array $args): Response
    {
        $tutorId = $request->getAttribute('tutor_id');
        $body    = $request->getParsedBody() ?? [];

        // Garante que o pet pertence ao tutor antes de atualizar
        if (Pet::buscarDoTutor($args['id'], $tutorId) === null) {
            return Res::error($response, 'Pet não encontrado.', 404);
        }

        $permitidos = ['nome', 'especie', 'raca', 'data_nascimento', 'recompensa', 'observacoes', 'foto_url'];
        $dados      = array_intersect_key($body, array_flip($permitidos));

        if (empty($dados)) {
            return Res::error($response, 'Nenhum campo válido para atualizar.', 422);
        }

        Pet::update($args['id'], $dados);

        return Res::success($response, ['mensagem' => 'Pet atualizado com sucesso.']);
    }

    public function deletar(Request $request, Response $response, array $args): Response
    {
        $tutorId = $request->getAttribute('tutor_id');

        if (Pet::buscarDoTutor($args['id'], $tutorId) === null) {
            return Res::error($response, 'Pet não encontrado.', 404);
        }

        Pet::desativar($args['id']);

        return Res::success($response, ['mensagem' => 'Pet removido com sucesso.']);
    }
}
