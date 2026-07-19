<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Historico;
use App\Helpers\Response as Res;

class LocalizacaoController
{
    // Público, sem auth
    // Recebe o que o front enviou após consentimento do usuário:
    //   { cidade, estado, bairro, precisao }
    // NENHUM IP É SALVO PARA GARANTIR CONFORMIDADE COM LGPD
    public function registrar(Request $request, Response $response, array $args): Response
    {
        $coleiraId = $args['id'];
        $body      = $request->getParsedBody() ?? [];

        // Rate limiting: máximo 20 registros por coleira por dia
        if (Historico::contarHoje($coleiraId) >= 20) {
            return Res::error($response, 'Limite de localização excedido, entre em contato pelo whatsapp.', 429);
        }

        $dados = [
            'coleira_id' => $coleiraId,
            'cidade'     => $body['cidade']    ?? null,
            'estado'     => $body['estado']    ?? null,
            'bairro'     => $body['bairro']    ?? null,
            'latitude'   => $body['latitude']  ?? null,
            'longitude'  => $body['longitude'] ?? null,
            'precisao'   => $body['precisao']  ?? 'aproximada', // 'exata' ou 'aproximada'
        ];

        Historico::registrar($dados);

        return Res::success($response, ['registrado' => true]);
    }
}
