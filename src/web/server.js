const express = require('express');
const path = require('path');
const app = express();
const port = process.env.PORT || 3000;

// A URL da API fica escondida no servidor! O código no GitHub fica genérico.
const PHP_API_URL = process.env.PHP_API_URL || 'URL_NAO_CONFIGURADA';

// O Node.js serve os arquivos estáticos (HTML/CSS)
app.use(express.static(path.join(__dirname, 'public')));

// Futuramente: aqui faremos as rotas do BFF escondendo a API PHP
// app.post('/login', (req, res) => { ... conversa com o PHP e gera cookie ... })

// Para rodar localmente no terminal
if (process.env.NODE_ENV !== 'production') {
  app.listen(port, async () => {
    console.log(`🚀 BFF Front-end rodando na porta ${port}`);
    console.log(`Tentando conectar na API PHP secreta...`);
    try {
        const response = await fetch(`${PHP_API_URL}/api/ping`);
        const data = await response.json();
        console.log(`✅ Status da API PHP retornado para o Node: ${data.status}`);
    } catch (error) {
        console.log(`❌ Erro ao falar com a API: Variável PHP_API_URL não configurada ou API offline.`);
    }
  });
}

// Exporta o app para a Vercel transformar em Serverless Function
module.exports = app;
