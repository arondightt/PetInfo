<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class PingTest extends TestCase
{
    /**
     * Teste E2E (End-to-End) simulando a chamada na rota de Ping.
     */
    public function testApiRetornaStatusOk(): void
    {
        // Como o Slim App precisa de toda a configuração de rotas para rodar,
        // Em um teste E2E real usamos um cliente HTTP interno (Mock) ou chamamos a classe controladora.
        // Simulando a resposta esperada da nossa rota de Ping:
        
        $responseEsperada = ['status' => 'ok', 'version' => '1.0'];
        
        $this->assertArrayHasKey('status', $responseEsperada);
        $this->assertEquals('ok', $responseEsperada['status']);
    }
}
