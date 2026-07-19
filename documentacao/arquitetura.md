# PetInfo — Arquitetura do Sistema

Este documento descreve a infraestrutura e as decisões arquiteturais do PetInfo.

## 1. Visão Geral (Decoupled Architecture)

O PetInfo foi desenhado utilizando o conceito de **Decoupled Architecture** (Arquitetura Desacoplada). 
Em vez de um monólito rodando front e back-end juntos, dividimos a aplicação em duas frentes independentes:

- **Back-end (API REST):** Fica responsável exclusivamente pelos dados, regras de negócio e banco de dados. (Escrito em PHP).
- **Front-end (BFF + Cliente):** Responsável pela camada visual e regras de interface. Para maior segurança e performance, o front-end utiliza o padrão **BFF (Backend For Frontend)**. Um servidor (Node.js) atua como intermediário, validando sessões, consumindo APIs externas (como a de IP) e fazendo SSR (Server-Side Rendering) das páginas de perfil do pet para evitar "telas em branco" de carregamento, conversando de forma segura com a API em PHP.

### Por que Decoupled?
1. **Escalabilidade:** Podemos escalar a API (PHP) independentemente de quantos acessos a página web (Node.js) tem.
2. **Segurança e Intermediação:** O servidor Node.js intermediário blinda a API PHP, validando acessos e mantendo segredos (como chaves de APIs de mapas ou localização) protegidos no servidor em vez de expostos no navegador.
3. **Mobile:** Ao isolarmos a API, um futuro aplicativo mobile (React Native, Flutter) pode consumir a exata mesma API sem nenhuma mudança.

## 2. Hospedagem

- **API REST (PHP 8):** Hospedada no [Fly.io](https://fly.io/). Escolhido porque fornece uma infraestrutura robusta e escalável.
- **Banco de Dados (PostgreSQL):** [Supabase](https://supabase.com/). Funciona como nosso banco gerenciado e possui excelentes limites no plano gratuito.
- **Front-end:** [Vercel](https://vercel.com/) (quando for construído). Plataforma perfeita para front-ends modernos.

## 3. Autenticação (JWT + Cookies Seguros no Front)

A API do PetInfo é **Stateless** e utiliza **JSON Web Token (JWT)**.
Nós **NÃO** utilizamos Sessões nativas do PHP nem armazenamos estado de login no banco.

**Como funciona o fluxo de segurança com o BFF (Front-end Server):**
1. O usuário preenche e-mail e senha no navegador e envia para o seu servidor Node.js.
2. O Node.js repassa os dados para a API PHP (`POST /api/auth/login`).
3. A API PHP valida e devolve um token JWT assinado para o Node.js.
4. O servidor Node.js pega esse JWT e o encapsula em um **Cookie HttpOnly e Secure**. O Node.js entrega a resposta para o navegador do usuário carimbada com esse Cookie.
5. Nas próximas requisições do usuário no seu site, o navegador anexa automaticamente o Cookie para o Node.js. O Node.js extrai o JWT do cookie e aciona a API no cabeçalho `Authorization: Bearer <seu_token>`.

**Por que essa arquitetura é blindada?**
O navegador nunca tem acesso direto ao valor do token JWT via JavaScript, o que zera a chance de roubo por ataques XSS. Ao mesmo tempo, a API PHP fica limpa e rápida exigindo apenas o Bearer Token. Quem orquestra a "sessão via cookie" de forma blindada é o intermediário Node.js!

## 4. O Padrão "BaseModel"

Para evitar a escrita repetitiva de queries SQL básicas (`INSERT INTO`, `UPDATE SET`, `SELECT * FROM...`), utilizamos uma classe abstrata chamada `BaseModel`.

- `Pet::create(['nome' => 'Fido'])` injeta os dados dinamicamente usando PDO Seguro (prepared statements) e retorna a ID.
- Para queries mais complexas que envolvem `JOIN` ou lógicas de negócio avançadas (ex: o método `buscarPerfil()`), nós não usamos o BaseModel e escrevemos o PDO cru diretamente na classe filha (ex: `Coleira.php`), garantindo assim performance e customização onde necessário.

## 5. Localização, LGPD e Privacidade

O sistema de localização de coleiras foi feito seguindo a Lei Geral de Proteção de Dados:
- Nós **NÃO** armazenamos Endereços de IP de quem acessa o perfil do animal, para evitar identificação indevida de terceiros.
- O front-end é responsável por pedir a localização aproximada (via API de IP no cliente) ou exata (via GPS do navegador) apenas quando autorizado pelo usuário que encontrou o pet.
- Salva-se apenas a `cidade`, `estado`, `bairro` e a enumeração `precisao` ("exata" ou "aproximada").
- **Rate Limit:** Para evitar ataques de spam de localização estourando o espaço no banco de dados, a API limita a **20 registros de localização por coleira por dia**. A requisição 21 retorna 200 (silenciosa) mas não insere no DB.
