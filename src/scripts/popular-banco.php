<?php
declare(strict_types=1);

require_once __DIR__ . '/../api/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../api');
$dotenv->safeLoad();

use App\Models\Tutor;
use App\Models\Pet;

try {
    // Apaga se já existir para não dar erro de duplicate key
    require __DIR__ . '/limpar-registros.php';

    echo "Criando usuário de teste...\n";
    $tutorId = Tutor::create([
        'nome' => 'Tutor de Teste',
        'email' => 'teste@petinfo.com',
        'senha' => '123456',
        'whatsapp' => '11999999999',
        'instagram' => 'testepet'
    ]);

    echo "Usuário criado. ID: $tutorId\n";

    echo "Criando pet de teste...\n";
    $petId = Pet::create([
        'tutor_id' => $tutorId,
        'nome' => 'Rex Teste',
        'especie' => 'Cachorro',
        'raca' => 'Vira-lata',
        'data_nascimento' => '2020-01-01',
        'recompensa' => 'R$ 100',
        'observacoes' => 'Manso, mas assustado.'
    ]);

    echo "Pet criado. ID: $petId\n";
    echo "\n=== BANCO POPULADO COM SUCESSO ===\n";
    echo "Email: teste@petinfo.com\n";
    echo "Senha: 123456\n";
    echo "==================================\n";

} catch (Exception $e) {
    echo "Erro ao popular o banco: " . $e->getMessage() . "\n";
}
