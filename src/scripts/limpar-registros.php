<?php
declare(strict_types=1);

require_once __DIR__ . '/../api/vendor/autoload.php';

// Carrega as variáveis de ambiente e configura o Banco da pasta api
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../api');
$dotenv->safeLoad();

use App\Database;

try {
    $pdo = Database::getInstance();
    
    echo "Limpando registros do usuário de teste...\n";
    $stmt = $pdo->prepare("DELETE FROM tutores WHERE email = 'teste@petinfo.com'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "Apagados dados de teste anteriores (pets, coleiras e histórico apagados em cascata).\n";
    } else {
        echo "Nenhum usuário teste@petinfo.com encontrado para limpar.\n";
    }
} catch (Exception $e) {
    echo "Erro ao limpar: " . $e->getMessage() . "\n";
}
