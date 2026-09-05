const express = require('express');
const path = require('path');
const app = express();
const port = process.env.PORT || 3000;

// A URL da API fica escondida no servidor! O código no GitHub fica genérico.
const PHP_API_URL = process.env.PHP_API_URL || 'URL_NAO_CONFIGURADA';

// O Node.js serve os arquivos estáticos (HTML/CSS)
app.use(express.static(path.join(__dirname, 'public')));

// Rota de teste: O navegador acessa o Node, e o Node acessa a API Interna!
app.get('/api/status', async (req, res) => {
  console.log("💻 [LOG DO SERVIDOR NODE] Recebemos um pedido na rota /api/status! Consultando API interna...");
  try {
      const response = await fetch(`${PHP_API_URL}/api/ping`);
      const apiData = await response.json();
      console.log("💻 [LOG DO SERVIDOR NODE] Sucesso! A API interna respondeu OK.");
      
      res.status(200).json({
          success: true,
          message: "Conexão com os serviços internos estabelecida com sucesso.",
          data: apiData
      });
  } catch (error) {
      console.error("💻 [LOG DO SERVIDOR NODE] Erro! Falha de comunicação com a API interna.", error.message);
      res.status(500).json({ 
          success: false,
          message: "Serviço temporariamente indisponível.", 
          data: null 
      });
  }
});

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
