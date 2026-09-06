# PetInfo Web (BFF + Front-end)

Este diretório contém a camada de apresentação (Front-end) da plataforma PetInfo, operando no padrão **BFF (Backend For Frontend)**. 

Ele é construído usando **Node.js, Express e EJS** no lado do servidor, e **Vanilla JS + CSS puro** no lado do cliente (navegador).

---

## 🏛️ O que é o Padrão BFF?

O navegador do usuário final **nunca** se comunica diretamente com a API ou com o Banco de Dados. 
Todo o Front-end faz requisições apenas para as rotas em Node.js (`server.js`). O Node.js atua como intermediário, vai até a API, coleta os dados e devolve para o navegador já formatado e seguro.

Isso previne o vazamento da infraestrutura backend, oculta a URL real da API e protege chaves sensíveis.

---

## 📁 Estrutura de Diretórios

```text
src/web/
├── package.json
├── server.js               ← Ponto de entrada do Vercel e rotas Express
├── src/                    ← Lógica interna do BFF (Controllers, Middlewares)
├── tests/                  ← Testes automatizados do Node.js (Jest/Supertest)
├── views/                  ← Páginas HTML injetadas com variáveis via EJS
│   ├── layout.ejs          ← Layout mestre (Moldura global)
│   ├── home.ejs
│   └── pet/
└── public/                 ← Tudo que é exposto publicamente para o navegador
    └── assets/
        ├── css/
        ├── img/
        └── js/             ← JS isolado em Módulos (ES Modules)
            ├── global.js   ← Funções comuns a todas as páginas
            └── home.js
```

---

## 🛠️ Pré-requisitos para Desenvolvimento

- **Node.js** (versão 20 ou superior recomendada)
- **NPM** (já vem com o Node)

---

## 🚀 Como Inicializar o Projeto (Localmente)

1. **Instale as Dependências:**
   Abra o terminal **nesta pasta (`src/web`)** e instale o Express, EJS e bibliotecas de teste:
   ```bash
   npm install
   ```

2. **Configure a Variável da API:**
   O BFF precisa saber onde a API PHP está rodando. Crie um arquivo `.env` (ou exporte no terminal):
   ```env
   PHP_API_URL=http://localhost:8080
   ```
   *(No ambiente de produção da Vercel, essa variável aponta para o domínio do Fly.io).*

3. **Inicie o Servidor:**
   ```bash
   npm start
   ```
   Acesse a aplicação em `http://localhost:3000`.

---

## 🧪 Rodando os Testes (Jest)

A Vercel está configurada para bloquear o deploy se os testes do Front-end falharem. Para rodar a simulação e verificar as rotas localmente, utilize:

```bash
npm run test
```
