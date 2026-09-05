<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

// Variáveis de ambiente
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

// Cria o app Slim
$app = AppFactory::create();

// Registra middlewares (CORS, parsing, erros)
require_once __DIR__ . '/Middleware.php';

// Registra todas as rotas
require_once __DIR__ . '/Routes.php';

return $app;
