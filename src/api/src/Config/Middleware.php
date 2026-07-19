<?php
declare(strict_types=1);

// ================================================================
// Middleware de parsing — lê JSON do body e popula $request->getParsedBody()
// ================================================================
$app->addBodyParsingMiddleware();

// ================================================================
// Middleware de roteamento — resolve qual rota bater
// ================================================================
$app->addRoutingMiddleware();

// ================================================================
// Middleware de erro — em dev mostra detalhes, em prod esconde
// ================================================================
$app->addErrorMiddleware(
    displayErrorDetails: $_ENV['APP_ENV'] === 'development',
    logErrors:           true,
    logErrorDetails:     true
);

// ================================================================
// CORS — libera o front-end (Vercel) a chamar a API (Fly.io)
// Sem isso o navegador bloqueia as requisições cross-origin
// ================================================================
$app->add(function ($request, $handler) {

    // Preflight: navegador pergunta "posso fazer isso?" antes da requisição real
    // Deve responder 200 imediatamente, sem chegar nos controllers
    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response();
        return $response
            ->withHeader('Access-Control-Allow-Origin',      $_ENV['FRONT_URL'] ?? '*')
            ->withHeader('Access-Control-Allow-Methods',     'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers',     'Content-Type, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withStatus(200);
    }

    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin',      $_ENV['FRONT_URL'] ?? '*')
        ->withHeader('Access-Control-Allow-Methods',     'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers',     'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});
