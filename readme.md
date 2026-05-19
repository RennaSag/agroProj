# Chave Entomológica - Sistema de Classificação de Insetos

Sistema web para classificação entomológica de insetos da Classe Insecta. Permite que alunos identifiquem famílias de insetos por meio de chaves dicotômicas interativas. Possui painel administrativo para gerenciamento de ordens, famílias e chaves.

Desenvolvido para a disciplina de Entomologia do IF Goiano.

---

## Acesso

- **Site:** [link do Render aqui]
- **Admin:** [link do Render]/admin/login.php

---

## Estrutura do Projeto

```
/
|-- index.php              - página principal (listagem de ordens)
|-- chave.php              - interface da chave dicotômica
|-- api.php                - API pública (JSON)
|-- Dockerfile             - configuração do container para deploy
|-- .env.example           - modelo das variáveis de ambiente
|-- assets/
|   |-- css/               - estilos separados por página
|   └── js/                - scripts JS
|-- includes/
|   |-- config.php         - carregamento das variáveis do .env
|   └── db.php             - conexão com banco e funções auxiliares
|-- admin/
|   |-- index.php          - dashboard administrativo
|   |-- ordens.php         - CRUD de ordens
|   |-- familias.php       - CRUD de famílias
|   |-- chaves.php         - CRUD de passos da chave dicotômica
|   |-- admins.php         - gerenciamento de administradores
|   |-- login.php          - autenticação do admin
|   |-- logout.php         - logout
|   └── check_auth.php     - controle de sessão
|-- database/
|   |-- entomologia.sql        - schema original (MySQL/MariaDB)
|   └── entomologia_postgres.sql - schema para PostgreSQL (Neon)
└── docs/
    └── Diagrama de caso de uso.PNG
```

---

## Stack

- **Backend:** PHP 8.2 com PDO
- **Banco de dados:** PostgreSQL (Neon)
- **Frontend:** HTML, CSS e JavaScript vanilla
- **Deploy:** Render (Docker)
- **Fontes:** Playfair Display e Source Sans 3

---

## Configuração Local

### Pré-requisitos

- PHP 8.2+ com extensões `pdo` e `pdo_pgsql`
- Acesso a um banco PostgreSQL (local ou Neon)

### Passo a passo

1. Clone o repositório:
   ```bash
   git clone https://github.com/RennaSag/agroProj.git
   cd agroProj
   ```

2. Copie o arquivo de variáveis de ambiente:
   ```bash
   cp .env.example .env
   ```

3. Preencha o `.env` com suas credenciais:
   ```
   DB_HOST=seu_host
   DB_USER=seu_usuario
   DB_PASS=sua_senha
   DB_NAME=neondb
   ```

4. Importe o schema no banco:
   ```
   database/entomologia_postgres.sql
   ```

5. Suba um servidor PHP local apontando para a raiz do projeto.

---

## Deploy (Render + Neon)

O deploy é feito via Docker no Render e dispara automaticamente a cada push na branch `postgres`.

### Variáveis de ambiente no Render

Configure em **Environment → Environment Variables**:

| Variável | Descrição |
|----------|-----------|
| `DB_HOST` | Host do banco no Neon (sem `-pooler`) |
| `DB_USER` | Usuário do banco |
| `DB_PASS` | Senha do banco |
| `DB_NAME` | Nome do banco (geralmente `neondb`) |

> **Atenção:** use o host direto do Neon, não o host do pooler. Remova `-pooler` do hostname se aparecer na connection string.

### Observação sobre imagens

O Render não possui disco persistente no plano gratuito. Imagens enviadas pelo admin ficam salvas apenas enquanto o container estiver rodando — um novo deploy apaga a pasta `uploads/`. Para persistência de imagens, integrar com Cloudinary ou similar.

---

## API Pública

Endpoint base: `api.php?action=<acao>`

| action | parâmetros | descrição |
|--------|------------|-----------|
| `ordens` | — | lista todas as ordens ativas |
| `ordem` | `id` (int) | retorna dados de uma ordem e suas famílias |
| `passos` | `ordem_id` (int) | retorna todos os passos da chave dicotômica |
| `familia` | `id` (int) | retorna dados de uma família |

---

## Banco de Dados

### Tabelas

**admins** — usuários do painel administrativo

**ordens** — ordens e subordens de insetos, com características em JSON, exemplos e importância agrícola

**familias** — famílias vinculadas a uma ordem

**chave_passos** — passos da chave dicotômica, com texto e imagem para cada opção (sim/não) e destino (próximo passo ou família resultado)

---

## Colaboradores

- Para contribuir: faça um push na branch `postgres` e o Render faz o deploy automaticamente.
- Para acesso ao painel admin: solicite ao responsável pelo projeto.
