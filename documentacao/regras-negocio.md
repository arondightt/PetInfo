# Regras de Negócio

## Perfil Público do Pet

O que é exibido quando alguém escaneia a tag (`Coleira::buscarPerfil()`):

| Campo | Exibido? | Observação |
|---|---|---|
| Nome do pet | ✅ | |
| Foto | ✅ | |
| Espécie, Raça, Idade | ✅ | Idade é calculada via `data_nascimento` |
| Recompensa | ✅ | Caso o tutor ofereça |
| Observações | ✅ | Alergias, medicamentos, comportamento |
| Nome do tutor | ✅ | (Novo requisito) |
| Email | ❌ | Privacidade |
| WhatsApp / Instagram | ✅ | Link direto |
| Última Localização | ✅ | Traz cidade, estado, bairro |

## Identificadores e Padrão de ID

- **Tutores e Pets (UUID):** Chaves primárias internas são UUIDs (`gen_random_uuid()`) para segurança de escalabilidade.
- **Coleiras (String 12 chars):** O ID da coleira é uma string curta de 12 caracteres (ex: `a1b2c3d4e5f6`). 
  - **Motivo:** Esse ID vai impresso no QR Code ou Tag física. Se usássemos UUID, a URL seria longa demais (`pettag.com/p/123e4567-e89b-12d3...`), o que encarece a gravação de tags NFC com pouca memória, e dificulta a leitura do QR Code pela câmera (QR codes longos têm pontilhados muito densos). 12 caracteres alfanuméricos são fáceis de ler e suportam bilhões de combinações.

## Rate Limiting de Scans e Localização

- Limite: **20 registros de localização por coleira por dia**.
- Como funciona a inserção: O método `Historico::registrar` cria uma linha nova (INSERT) formando uma *timeline* do percurso do animal.
- Quando o limite de 20 é atingido: a API retorna erro `429 Too Many Requests` com a mensagem "Limite de localização excedido, entre em contato pelo whatsapp".

## Geolocalização e LGPD

- **Como funciona sem ferir a LGPD?** A LGPD protege "dados pessoais" (dados que identificam um indivíduo).
  - O **IP bruto NUNCA é salvo no banco**. Se salvássemos o IP, seria um dado pessoal. Em vez disso, o Front-end consulta uma API pública de IP, extrai apenas a `cidade` e o `estado`, e descarta o IP imediatamente. Como "São Paulo/SP" não identifica uma pessoa, o dado armazenado é anônimo e totalmente seguro perante a lei.
  - Isso permite capturar curiosos (localização aproximada de forma invisível) sem ferir a privacidade.
- Já o GPS (localização exata) salva as coordenadas `latitude` e `longitude` no banco. Isso exige **consentimento explícito**, mas o próprio navegador (Chrome, Safari) se encarrega de jogar o pop-up ("Deseja permitir que este site acesse sua localização?"). A LGPD entende esse aceite do pop-up como consentimento válido. O ideal é o front-end colocar um texto antes ("Clique para enviar a localização exata do pet").
- O Tutor pode limpar o histórico de localizações (`DELETE`) de qualquer pet a qualquer momento, garantindo o "Direito ao Esquecimento".

## Coleiras (Vincular / Desvincular)

As coleiras são gerenciadas separadamente dos Pets por questões de logística física:
- Quando o usuário cria um pet, o ID da coleira pode ser informado no futuro (usando `PATCH /api/coleiras/{id}/vincular`).
- Uma coleira pode existir sem estar vinculada a nenhum pet (`pet_id = NULL`).
- Status possíveis: `ativa`, `inativa`.

## Autenticação

- A API funciona 100% via **JWT (JSON Web Tokens)** e é **Stateless**.
- O Token expira após **7 dias**.
- Senhas armazenadas com `password_hash()` (bcrypt).
- **Gerenciamento Seguro:** A API não emite e nem lê Cookies nativos do PHP. O servidor Front-end (Node.js/Express) é quem deve gerenciar a sessão do usuário criando um **Cookie HttpOnly** para armazenar o JWT no navegador, garantindo proteção total contra XSS. Para consumir dados na API, o Node.js extrai o JWT do cookie e envia no cabeçalho `Authorization: Bearer <token>`.
