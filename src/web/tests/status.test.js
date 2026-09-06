const request = require('supertest');
const app = require('../server.js'); // Importa o servidor Express sem ligar a porta

describe('Teste das Rotas do BFF (Node.js)', () => {
    
    it('Deve retornar HTTP 200 e success: true na rota /api/status', async () => {
        // Supertest simula uma requisição do navegador para o Node
        const response = await request(app).get('/api/status');
        
        // As nossas "afirmações" (asserts) do Jest
        expect(response.statusCode).toBe(200);
        expect(response.body.success).toBe(true);
        expect(response.body).toHaveProperty('message');
    });

});
