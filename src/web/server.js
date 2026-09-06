const express = require('express');
const path = require('path');
const app = express();
const port = process.env.PORT || 3000;

const PHP_API_URL = process.env.PHP_API_URL || 'URL_NAO_CONFIGURADA';

// Configuração do Template Engine (EJS)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Arquivos Estáticos (CSS, JS do Front)
app.use(express.static(path.join(__dirname, 'public')));

// Rota Principal (Home)
app.get('/', (req, res) => {
    // Renderiza a view home passando qual JS ela deve auto-carregar
    res.render('layout', { 
        page: 'home', 
        title: 'PetInfo - Início', 
        script: 'home.js' 
    });
});

// Rota Dinâmica do Pet (pet/:id)
app.get('/pet/:id', (req, res) => {
    const petId = req.params.id; // Captura o ID da URL
    res.render('layout', { 
        page: 'pet/profile', 
        title: `Perfil do Pet #${petId}`, 
        script: 'views/pet/profile.js', // JS isolado só para essa tela
        petId: petId 
    });
});

// Rota 401 - Não Autorizado
app.get('/401', (req, res) => {
    res.status(401).render('layout', { page: 'errors/401', title: 'Acesso Negado', script: null });
});

// Rota 404 (Sempre a última rota!)
app.use((req, res) => {
    res.status(404).render('layout', { page: 'errors/404', title: 'Página não encontrada', script: null });
});

// Para rodar localmente no terminal
if (process.env.NODE_ENV !== 'production') {
  app.listen(port, () => console.log(`🚀 BFF Front-end rodando na porta ${port}`));
}

module.exports = app;
