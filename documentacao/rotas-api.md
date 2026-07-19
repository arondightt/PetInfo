# Rotas da API

## Públicas (sem autenticação)

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/api/ping` | Health check (CI/CD após deploy) |
| `GET` | `/api/perfil/{id_coleira}` | JSON do perfil público do pet (traz dados do pet, tutor e última localização) |
| `POST` | `/api/localizacao/{id_coleira}` | Registra scan (limitado a 20 por dia por coleira) |
| `POST` | `/api/auth/cadastro` | Cria conta de tutor |
| `POST` | `/api/auth/login` | Login (retorna JWT) |

## Protegidas (JWT obrigatório)

### Tutor

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/api/tutor` | Dados do tutor logado |
| `PATCH`| `/api/tutor` | Atualiza perfil do tutor (parcial) |

### Pets

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/api/pets` | Lista todos os pets do tutor |
| `POST` | `/api/pets` | Cadastra novo pet |
| `GET` | `/api/pets/{id}` | Detalhes de um pet |
| `PATCH`| `/api/pets/{id}` | Edita pet (parcial) |
| `DELETE` | `/api/pets/{id}` | Remove pet (Soft Delete) |

### Coleiras

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/api/coleiras` | Lista coleiras do tutor |
| `POST` | `/api/coleiras/gerar` | Gera novo ID de coleira (hash alfanumérico) |
| `PATCH`| `/api/coleiras/{id}/vincular` | Vincula coleira a um pet |
| `PATCH`| `/api/coleiras/{id}/desvincular` | Desvincula coleira |
| `DELETE` | `/api/coleiras/{id}` | Desativa coleira |

### Histórico

| Método | Rota | Descrição |
|---|---|---|
| `GET` | `/api/historico/{coleira_id}` | Histórico de localizações (Timeline) |
| `DELETE` | `/api/historico/{coleira_id}` | Limpa histórico (LGPD) |

## Formato das Respostas

Todas as respostas são JSON:

```json
// Sucesso
{ "success": true, "data": { ... } }

// Erro
{ "success": false, "error": "Mensagem de erro", "code": 422 }
```

## Autenticação nas Rotas Protegidas

A API utiliza uma arquitetura **Stateless**:
- Toda requisição em rotas protegidas deve enviar o Header: `Authorization: Bearer <token_jwt>`
- A emissão e gerenciamento de cookies é responsabilidade exclusiva do servidor intermediário Front-end (Node.js). A API em PHP não gera nem lê cookies diretamente.
