<?php
declare(strict_types=1);

require_once __DIR__ . '/../api/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../api');
$dotenv->safeLoad();

use App\Models\Tutor;
use App\Helpers\JwtToken;

try {
    $tutor = Tutor::buscarPorEmail('teste@petinfo.com');

    if (!$tutor) {
        die("Usuário de teste não encontrado. Execute 'php popular-banco.php' primeiro.\n");
    }

    $token = JwtToken::generate(['tutor_id' => (string) $tutor['id']]);

    echo "\n=== SEU TOKEN JWT (Válido por 7 dias) ===\n\n";
    echo "Bearer " . $token . "\n\n";
    echo "Copie e cole isso no header 'Authorization' do seu Postman/Insomnia.\n";
    echo "=========================================\n";

} catch (Exception $e) {
    echo "Erro ao gerar token: " . $e->getMessage() . "\n";
}
