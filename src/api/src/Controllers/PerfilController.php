<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Coleira;
use App\Helpers\Response as Res;

class PerfilController
{
    // Público, sem auth
    public function show(Request $request, Response $response, array $args): Response
    {
        $perfil = Coleira::buscarPerfil($args['id']);

        if ($perfil === null) {
            return Res::error($response, 'Pet não encontrado ou tag inativa.', 404);
        }

        return Res::success($response, $perfil);
    }
}
