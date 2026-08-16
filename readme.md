# Chave Entomológica

Sistema web para classificação de insetos da Classe Insecta, desenvolvido como projeto acadêmico no **IF Goiano**. Permite que alunos identifiquem famílias de insetos por meio de chaves dicotômicas interativas, com painel administrativo completo para gerenciamento do conteúdo.

**[Acesse o sistema](https://agroproj-plgg.onrender.com/)**

---

## Funcionalidades

**Área pública**
- Listagem de ordens em grid de cards com imagem e descrição
- Modal com características, exemplos e importância agrícola de cada ordem
- **Specimen Match**: navegação passo a passo pela chave dicotômica com comparação visual lado a lado entre duas alternativas

**Painel administrativo** *(requer autenticação)*
- Dashboard com contadores gerais
- CRUD completo de ordens, famílias e passos da chave dicotômica
- Upload de imagens para ordens, famílias e alternativas da chave
- Gerenciamento de administradores

---

## Tecnologias

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP (PDO + prepared statements) |
| Banco de dados | PostgreSQL (Neon) |
| Frontend | HTML, CSS, JavaScript vanilla |
| Infraestrutura | Docker + Render |
| Fontes | Playfair Display, Source Sans 3 |

---

## Estrutura do Projeto

```
/
├── index.php                   # Página principal - listagem de ordens
├── chave.php                   # Interface da chave dicotômica (Specimen Match)
├── api.php                     # Endpoint JSON interno
├── Dockerfile                  # Configuração do container
├── .env.example                # Modelo das variáveis de ambiente
├── .gitignore
├── admin/
│   ├── index.php               # Dashboard administrativo
│   ├── ordens.php              # CRUD de ordens
│   ├── familias.php            # CRUD de famílias
│   ├── chaves.php              # CRUD de passos da chave dicotômica
│   ├── admins.php              # Lista de e-mails do SUAP autorizados
│   ├── configuracoes.php       # Configurações do sistema
│   ├── login.php               # Tela de login (botão "Entrar com SUAP")
│   ├── suap_login.php          # Inicia o fluxo OAuth2 com o SUAP
│   ├── suap_callback.php       # Recebe o retorno do SUAP e abre a sessão
│   ├── logout.php              # Controle de logout
│   └── check_auth.php          # Middleware de autenticação
├── assets/
│   ├── css/
│   │   ├── ui-base.css         # Estilos base compartilhados
│   │   ├── site-home.css       # Estilos da página principal
│   │   ├── site-chave.css      # Estilos da chave dicotômica
│   │   ├── admin-index.css
│   │   ├── admin-ordens.css
│   │   ├── admin-familias.css
│   │   ├── admin-chaves.css
│   │   ├── admin-admins.css
│   │   ├── admin-login.css
│   │   └── admin-responsive.css
│   └── js/
│       └── admin-layout.js
├── includes/
│   ├── config.php              # Carregamento das variáveis do .env
│   ├── db.php                  # Conexão com banco e funções auxiliares
│   └── suap.php                # Cliente OAuth2 do SUAP (login)
├── database/
│   ├── entomologia_postgresql.sql  # Schema PostgreSQL usado em produção (Neon)
│   └── entomologia_mysql.sql       # Dump original em MySQL/MariaDB (histórico)
├── uploads/
│   └── insetos/                # Imagens enviadas via painel admin
├── docs/
│   ├── telas/                  # Capturas de tela do sistema
│   ├── Diagrama de caso de uso.PNG
│   └── RELATORIO_ALTERACOES_UI_ACESSIBILIDADE.md
└── graphify-out/               # Mapeamento de dependências do projeto
```

---

## Como rodar localmente

### Pré-requisitos
- Docker e Docker Compose **ou** PHP 8+ com PostgreSQL

### Com Docker

```bash
git clone https://github.com/RennaSag/agroProj.git
cd agroProj
cp .env.example .env
# Preencha as variáveis de ambiente no .env
docker compose up --build
```

### Sem Docker

```bash
git clone https://github.com/RennaSag/agroProj.git
cd agroProj
cp .env.example .env
# Preencha as variáveis de ambiente no .env
# Importe database/entomologia_postgresql.sql no seu PostgreSQL
# Sirva o projeto com PHP built-in server ou Apache/Nginx
php -S localhost:8000
```

---

## Variáveis de Ambiente

Copie `.env.example` para `.env` e preencha com o exemplo, ou use o painel environment do render:

```env
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=

# Login via SUAP OAuth2
SUAP_BASE_URL=https://suap.ifgoiano.edu.br
SUAP_CLIENT_ID=
SUAP_CLIENT_SECRET=
SUAP_REDIRECT_URI=
```

`SUAP_CLIENT_ID` e `SUAP_CLIENT_SECRET` vêm de uma aplicação OAuth2 cadastrada em `https://suap.ifgoiano.edu.br/o/applications/` (tipo *Confidential*, grant *Authorization code*). O `SUAP_REDIRECT_URI` deve ser exatamente `<URL do site>/admin/suap_callback.php` e precisa estar cadastrado como Redirect URI dessa aplicação.

---

## Banco de Dados

O schema em produção está em `database/entomologia_postgresql.sql` (PostgreSQL/Neon). `database/entomologia_mysql.sql` é o dump original em MySQL/MariaDB, mantido como histórico do projeto. As principais tabelas são:

- **admins** - usuários do painel administrativo
- **ordens** - ordens e subordens de insetos
- **familias** - famílias vinculadas a cada ordem
- **chave_passos** - passos da chave dicotômica com caminhos sim/não e resultados

---

## Upload de Imagens

- Diretório: `uploads/insetos/`
- Formatos aceitos: JPG, PNG, WebP
- Tamanho máximo: 5 MB

---

## Autenticação

Login via **SUAP OAuth2** (Authorization Code Grant). Ao clicar em "Entrar com SUAP", o usuário é redirecionado para autenticar no SUAP da instituição; o SUAP retorna um `code` que é trocado por um token de acesso, usado para consultar `/api/eu/` e obter o e-mail institucional do usuário. Esse e-mail é conferido contra a tabela `admins` (lista de e-mails autorizados, gerenciada em `admin/admins.php`) — só quem estiver cadastrado lá consegue abrir uma sessão no painel. A função `requireAdmin()` protege todas as rotas administrativas, redirecionando para a tela de login caso não autenticado.
