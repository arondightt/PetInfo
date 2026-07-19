<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Historico;
use App\Helpers\Response as Res;

class HistoricoController
{
    public function listar(Request $request, Response $response, array $args): Response
    {
        $historico = Historico::listarPorColeira($args['coleira_id']);

        return Res::success($response, $historico);
    }

    // Apaga histórico (direito LGPD)
    public function limpar(Request $request, Response $response, array $args): Response
    {
        Historico::limpar($args['coleira_id']);

        return Res::success($response, ['mensagem' => 'Histórico apagado com sucesso.']);
    }
}
