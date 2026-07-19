# Contribuindo com o PetTag

Obrigado pelo interesse em contribuir! Este documento explica como funciona o processo.

---

## 📋 Antes de Contribuir

Ao submeter um Pull Request, você concorda que sua contribuição pode ser licenciada
tanto sob **AGPL-3.0** quanto sob **licença comercial** pelo mantenedor do projeto.

Isso é necessário para manter o modelo de sustentabilidade do projeto (dual licensing).
Se você não concordar com isso, ainda pode fazer um fork sob AGPL-3.0.

---

## 🛠️ Como Contribuir

1. Faça um fork do repositório
2. Crie uma branch: `git checkout -b feature/minha-feature`
3. Faça suas alterações e commit: `git commit -m "feat: descrição clara"`
4. Push: `git push origin feature/minha-feature`
5. Abra um Pull Request descrevendo o que foi feito e por quê

---

## 📐 Padrões do Projeto

- **PHP:** PSR-12 (formatação), mensagens de erro em português
- **Commits:** Conventional Commits (`feat:`, `fix:`, `docs:`, `refactor:`)
- **Segurança:** nunca commitar credenciais, IPs brutos ou dados pessoais de usuários

---

## 🐛 Reportando Bugs

Abra uma Issue com:
- Descrição do comportamento esperado vs. o observado
- Passos para reproduzir
- Versão do PHP/Docker/navegador usados
