# PetInfo API

Este é o diretório raiz do back-end em PHP 8.2 + Slim 4 da plataforma PetInfo.

## Executando Localmente (Desenvolvimento)
Para subir o servidor embutido com hot-reload pelo Docker:
```bash
docker compose up -d
```
A API estará disponível em `http://localhost:8080`.

## Scripts de Teste
Existem scripts auxiliares na pasta `../scripts/` (um nível acima) para ajudar a testar as rotas JWT via Postman ou Insomnia:
- `php ../scripts/popular-banco.php` -> Cria o usuário `teste@petinfo.com` (senha: 123456)
- `php ../scripts/gerar-token.php` -> Gera e imprime um JWT válido de 7 dias para o usuário
- `php ../scripts/limpar-registros.php` -> Apaga do banco o usuário de teste e tudo associado

## Documentação Completa
Toda a arquitetura, regras de negócio e endpoints estão documentados na raiz do repositório, na pasta `../../documentacao`.
