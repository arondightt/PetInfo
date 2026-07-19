# PetInfo API

Este é o diretório raiz do back-end em PHP 8.2 + Slim 4 da plataforma PetInfo. 
Esta API é completamente desacoplada e se comunica com o front-end via JSON e autenticação JWT.

---

## 🛠️ Pré-requisitos para Desenvolvimento

Para rodar ou dar manutenção nesta API na sua máquina local, você precisará ter instalado:

1. **PHP 8.2 ou superior**: Necessário para rodar os scripts localmente e instalar pacotes.
2. **Composer**: Gerenciador de dependências do PHP.
3. **Docker e Docker Compose**: (Opcional, mas recomendado) Para rodar o ambiente sem se preocupar com configurações de Apache/Nginx.

---

## 🚀 Como Inicializar o Projeto (Primeira Vez)

Se você acabou de clonar este repositório, a pasta `vendor/` (com as bibliotecas) não existe. Siga os passos abaixo para baixar as dependências e iniciar a API:

### 1. Instale as Dependências do PHP
Abra o terminal **nesta pasta (`src/api`)** e rode:
```bash
composer install
```
Isso vai ler o arquivo `composer.lock` e baixar exatamente as mesmas versões testadas do Slim, Firebase JWT e Dotenv, criando a pasta `vendor/`.

### 2. Configure as Variáveis de Ambiente
Crie um arquivo `.env` baseado no arquivo de exemplo:
```bash
cp .env.example .env
```
Abra o `.env` e coloque as credenciais de conexão do Supabase (Banco de Dados PostgreSQL).

### 3. Suba o Servidor (Docker)
Com as dependências instaladas, você pode levantar a API usando o Docker:
```bash
docker compose up -d
```
A API estará acessível no seu navegador via: `http://localhost:8080`.
*(O Docker já está mapeando a pasta atual, então qualquer alteração que você fizer no código PHP será atualizada instantaneamente, sem precisar reiniciar o contêiner).*

---

## 🧪 Scripts de Teste e População de Banco

No nível de `src/`, criamos scripts auxiliares para facilitar os testes das rotas via Postman ou Insomnia. Para executá-los, abra o terminal na pasta raiz do repositório (`PetInfo/`) e rode:

1. **Criar Dados de Teste:**
```bash
php src/scripts/popular-banco.php
```
*Cria o usuário `teste@petinfo.com` (senha: 123456) e insere um Pet de exemplo.*

2. **Gerar Token JWT:**
```bash
php src/scripts/gerar-token.php
```
*Gera e imprime no terminal um token JWT válido por 7 dias. Basta copiar a string `Bearer ...` e colar no Header `Authorization` do seu cliente HTTP para acessar rotas protegidas.*

3. **Limpar o Banco:**
```bash
php src/scripts/limpar-registros.php
```
*Apaga o usuário de teste. Pelo efeito cascata (CASCADE) configurado no banco, todos os pets, coleiras e históricos atrelados a ele também são deletados.*

---

## 📖 Documentação Completa

Toda a arquitetura, modelo do banco de dados (schema) e regras de negócio detalhadas estão documentadas na raiz do repositório, na pasta `../../documentacao`.
