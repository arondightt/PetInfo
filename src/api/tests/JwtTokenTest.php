<?php
declare(strict_types=1);

namespace Tests;

use App\Helpers\JwtToken;
use PHPUnit\Framework\TestCase;

class JwtTokenTest extends TestCase
{
    protected function setUp(): void
    {
        // Configura variáveis de ambiente simuladas para o contexto do teste
        $_ENV['JWT_SECRET'] = 'test_secret_key_12345';
        $_ENV['JWT_EXPIRY'] = 3600; // 1 hora
    }

    public function testGenerateTokenReturnsValidString(): void
    {
        $payload = ['tutor_id' => 99];
        $token = JwtToken::generate($payload);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testValidateTokenReturnsCorrectPayload(): void
    {
        $payload = ['tutor_id' => 99];
        $token = JwtToken::generate($payload);
        
        $decoded = JwtToken::validate($token);

        $this->assertIsArray($decoded);
        $this->assertEquals(99, $decoded['tutor_id']);
        $this->assertArrayHasKey('iat', $decoded);
        $this->assertArrayHasKey('exp', $decoded);
    }

    public function testValidateRejectsInvalidToken(): void
    {
        $invalidToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.invalid.signature';
        $decoded = JwtToken::validate($invalidToken);

        $this->assertFalse($decoded);
    }
}
