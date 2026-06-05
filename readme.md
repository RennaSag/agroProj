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
├── gerar_senha.php             # Utilitário para gerar hash de senha
├── Dockerfile                  # Configuração do container
├── .env.example                # Modelo das variáveis de ambiente
├── .gitignore
├── admin/
│   ├── index.php               # Dashboard administrativo
│   ├── ordens.php              # CRUD de ordens
│   ├── familias.php            # CRUD de famílias
│   ├── chaves.php              # CRUD de passos da chave dicotômica
│   ├── admins.php              # Gerenciamento de administradores
│   ├── configuracoes.php       # Configurações do sistema
│   ├── login.php               # Autenticação do admin
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
│   └── db.php                  # Conexão com banco e funções auxiliares
├── database/
│   └── entomologia.sql         # Estrutura e carga inicial do banco
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
# Importe database/entomologia.sql no seu PostgreSQL
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
```

---

## Banco de Dados

O schema completo está em `database/entomologia.sql`, com a variação em PostgreSQL. As principais tabelas são:

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

Login via sessão PHP com email e senha. A função `requireAdmin()` protege todas as rotas administrativas, redirecionando para a tela de login caso não autenticado.
