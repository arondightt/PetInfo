# 🐾 PetInfo

Plataforma open source de identificação e recuperação de animais de estimação perdidos via **tags NFC** e **QR Code**.

Qualquer pessoa que encontrar o animal escaneia o código com o celular → acessa o perfil público do pet → contata o tutor via WhatsApp instantaneamente. Sem instalar nenhum aplicativo.

---

## ✨ Como Funciona

1. Tutor cria conta gratuita e cadastra seu pet
2. A plataforma gera um link único e um QR Code
3. Tutor grava o link em uma tag NFC (tutorial disponível no site) ou imprime o QR Code
4. Se o pet se perder: qualquer pessoa escaneia → perfil do pet abre → botão direto para o WhatsApp do tutor
5. A plataforma registra silenciosamente a localização aproximada pelo IP e, opcionalmente, coordenadas GPS precisas (com consentimento)

---

## 🛠️ Stack

| Camada | Tecnologia |
|---|---|
| Web (BFF + Cliente) | Node.js, Express, HTML5, CSS3, jQuery |
| Back-end API | PHP 8.2 + Slim 4 |
| Banco de Dados | PostgreSQL (Supabase) |
| Storage | Supabase Storage |
| Container | Docker (Apache + PHP) |
| Deploy API | Fly.io (via GitHub Actions) |
| Deploy Web | Vercel |

---

## 🚀 Rodando Localmente

### Pré-requisitos
- Docker e Docker Compose instalados
- Conta no [Supabase](https://supabase.com) (gratuita)

### Passos

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/petinfo.git
cd petinfo

# 2. Configure as variáveis de ambiente
cp src/api/.env.example src/api/.env
# Edite o .env com suas credenciais do Supabase

# 3. Suba o ambiente
cd src/api
docker compose up -d

# 4. Acesse
# API:       http://localhost:8080
# Web:       Navegue até src/web/ ou inicie seu servidor Node
```

---

## 📁 Estrutura do Projeto

```
petinfo/
├── documentacao/         ← Arquitetura, regras de negócio
│   ├── rotas-api.md
│   ├── regras-negocio.md
│   └── arquitetura.md
├── src/
│   ├── api/              ← Back-end PHP (Slim 4)
│   │   ├── docker-compose.yml
│   │   ├── .env.example
│   │   └── src/          ← Código fonte da API
│   ├── database/         ← Schema do banco de dados
│   │   └── schema.sql
│   ├── web/              ← Front-end (Node/HTML/JS)
│   └── mobile/           ← (Futuro App)
├── LICENSE               ← AGPL-3.0
├── CONTRIBUTING.md
└── README.md
```

---

## 📜 Licença

Este projeto é licenciado sob **[AGPL-3.0](LICENSE)**.

Você pode usar, estudar, modificar e redistribuir livremente — desde que mantenha a mesma licença.

**Para uso comercial** sem as obrigações da AGPL (produto fechado, SaaS proprietário, white-label), entre em contato para uma licença comercial.

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Leia o [CONTRIBUTING.md](CONTRIBUTING.md) antes de abrir um Pull Request.
