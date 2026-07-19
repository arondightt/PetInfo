<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ColeiraController;
use App\Controllers\HistoricoController;
use App\Controllers\LocalizacaoController;
use App\Controllers\PerfilController;
use App\Controllers\PetController;
use App\Controllers\TutorController;
use App\Middleware\AuthMiddleware;

// ================================================================
// ROTAS PÚBLICAS
// Qualquer pessoa acessa — sem autenticação
// ================================================================

// Health check — confirma que a API está no ar (útil no CI/CD após deploy)
$app->get('/api/ping', function ($req, $res) {
    $res->getBody()->write(json_encode(['status' => 'ok']));
    return $res->withHeader('Content-Type', 'application/json');
});

// Perfil público do pet — acessado por quem achar o animal na rua via NFC/QR Code
$app->get('/api/perfil/{id}', [PerfilController::class, 'show']);

// Localização — recebe o que o front enviou após o usuário clicar no botão
// Pode chegar: { cidade, estado } ou { latitude, longitude } ou ambos
$app->post('/api/localizacao/{id}', [LocalizacaoController::class, 'registrar']);

// Autenticação
$app->post('/api/auth/cadastro', [AuthController::class, 'cadastro']);
$app->post('/api/auth/login',    [AuthController::class, 'login']);


// ================================================================
// ROTAS PROTEGIDAS
// Requerem JWT (header Authorization: Bearer ...)
// O AuthMiddleware valida o token e injeta o tutor_id no request
// ================================================================
$app->group('/api', function ($group) {

    // --- Tutor logado ---
    $group->get('/tutor',  [TutorController::class, 'me']);
    $group->patch('/tutor',  [TutorController::class, 'atualizar']);

    // --- Pets ---
    $group->get('/pets',         [PetController::class, 'listar']);
    $group->post('/pets',        [PetController::class, 'criar']);
    $group->get('/pets/{id}',    [PetController::class, 'detalhe']);
    $group->patch('/pets/{id}',    [PetController::class, 'atualizar']);
    $group->delete('/pets/{id}', [PetController::class, 'deletar']);

    // --- Coleiras (tags NFC/QR) ---
    $group->get('/coleiras',                  [ColeiraController::class, 'listar']);
    $group->post('/coleiras/gerar',           [ColeiraController::class, 'gerar']);
    $group->patch('/coleiras/{id}/vincular',    [ColeiraController::class, 'vincular']);
    $group->patch('/coleiras/{id}/desvincular', [ColeiraController::class, 'desvincular']);
    $group->delete('/coleiras/{id}',          [ColeiraController::class, 'deletar']);

    // --- Histórico de localizações ---
    $group->get('/historico/{coleira_id}',    [HistoricoController::class, 'listar']);
    $group->delete('/historico/{coleira_id}', [HistoricoController::class, 'limpar']);

})->add(new AuthMiddleware());
