# Documentacao do Sistema - Chave Entomologica

## Visao Geral

Sistema web para classificacao entomologica de insetos da Classe Insecta. Permite que alunos identifiquem familias de insetos por meio de chaves dicotomicas interativas. Possui painel administrativo para gerenciamento de ordens, familias e chaves.

---

## Estrutura do Projeto

```
/
|-- index.php              - pagina principal (listagem de ordens)
|-- chave.php              - interface da chave dicotomica
|-- api.php                - api publica (json)
|-- gerar_senha.php        - utilitario para gerar hash de senha
|-- teste.php              - utilitario de teste de autenticacao
|-- .env                   - configuracoes locais (nao versionar)
|-- .env.example           - modelo das configuracoes locais
|-- assets/
|   |-- css/               - estilos separados das paginas PHP
|   `-- js/                - scripts futuros separados das paginas PHP
|-- includes/
|   |-- config.php         - carregamento das variaveis do .env
|   `-- db.php             - conexao com banco e funcoes auxiliares
|-- admin/
|   |-- index.php          - dashboard administrativo
|   |-- ordens.php         - crud de ordens
|   |-- familias.php       - crud de familias
|   |-- chaves.php         - crud de passos da chave dicotomica
|   |-- admins.php         - gerenciamento de administradores
|   |-- login.php          - autenticacao do admin
|   |-- logout.php         - controle de logout
|   `-- check_auth.php     - controle de login
|-- database/
|   `-- entomologia.sql    - estrutura e carga inicial do banco
`-- docs/
    `-- Diagrama de caso de uso.PNG
```

---

## Banco de Dados

As credenciais do banco ficam no arquivo `.env`.
Use `.env.example` como modelo ao configurar o projeto em outra maquina.

### Tabelas

#### admins
| campo      | tipo         | descricao                    |
|------------|--------------|------------------------------|
| id         | int PK AI    | identificador                |
| nome       | varchar(100) | nome do administrador        |
| email      | varchar(150) | email (unico)                |
| senha      | varchar(255) | hash md5 da senha            |
| criado_em  | timestamp    | data de criacao              |

#### ordens
| campo               | tipo         | descricao                              |
|---------------------|--------------|----------------------------------------|
| id                  | int PK AI    | identificador                          |
| nome                | varchar(100) | nome da ordem ou subordem              |
| descricao           | text         | descricao geral                        |
| caracteristicas     | text         | json array de caracteristicas          |
| exemplos            | varchar(255) | exemplos de insetos                    |
| importancia_agricola| text         | relevancia agricola                    |
| imagem              | varchar(255) | caminho da imagem                      |
| ativo               | tinyint(1)   | visibilidade no site                   |
| ordem_exibicao      | int          | ordenacao na listagem                  |
| criado_em           | timestamp    | data de criacao                        |
| atualizado_em       | timestamp    | data de atualizacao                    |

#### familias
| campo      | tipo         | descricao                        |
|------------|--------------|----------------------------------|
| id         | int PK AI    | identificador                    |
| ordem_id   | int FK       | referencia a ordens              |
| nome       | varchar(100) | nome da familia                  |
| descricao  | text         | descricao da familia             |
| exemplos   | varchar(255) | exemplos de insetos              |
| imagem     | varchar(255) | caminho da imagem                |
| ativo      | tinyint(1)   | visibilidade no site             |

#### chave_passos
| campo                   | tipo         | descricao                                 |
|-------------------------|--------------|-------------------------------------------|
| id                      | int PK AI    | identificador                             |
| ordem_id                | int FK       | referencia a ordens                       |
| passo_numero            | int          | numero sequencial do passo                |
| pergunta                | text         | pergunta dicotomica                       |
| opcao_sim_texto         | varchar(255) | texto descritivo da opcao sim             |
| opcao_nao_texto         | varchar(255) | texto descritivo da opcao nao             |
| sim_leva_passo          | int          | numero do proximo passo se sim            |
| nao_leva_passo          | int          | numero do proximo passo se nao            |
| sim_resultado_familia_id| int FK       | familia identificada se sim               |
| nao_resultado_familia_id| int FK       | familia identificada se nao               |

---

## API Publica (api.php)

Endpoint: `api.php?action=<acao>`

| action     | parametros         | descricao                                   |
|------------|--------------------|---------------------------------------------|
| ordens     | nenhum             | lista todas as ordens ativas                |
| ordem      | id (int)           | retorna dados de uma ordem e suas familias  |
| passos     | ordem_id (int)     | retorna todos os passos da chave dicotomica |
| familia    | id (int)           | retorna dados de uma familia                |

---

## Funcionalidades

### Area Publica

- **Listagem de Ordens**: grid de cards com imagem, nome e botoes de acao
- **Modal de Descricao**: exibe caracteristicas, exemplos, importancia agricola e familias da ordem
- **Chave Dicotomica**: navegacao passo a passo com perguntas sim/nao, barra de progresso e resultado final com nome da familia, descricao e exemplos

### Area Administrativa

Requer autenticacao via sessao PHP.

- **Dashboard**: exibe contadores de ordens, familias e passos cadastrados
- **Ordens**: listagem, criacao, edicao e exclusao de ordens com upload de imagem
- **Familias**: listagem filtrada por ordem, criacao, edicao e exclusao com upload de imagem
- **Chaves Dicotomicas**: selecao de ordem, listagem dos passos, adicao e edicao de passos com definicao de destinos (proximo passo ou familia resultado)
- **Administradores**: listagem e cadastro de novos admins; exclusao de outros admins (nao de si mesmo)

---

## Autenticacao

- Login via formulario com email/nome e senha
- Senha armazenada como hash MD5 (legado; novos admins usam password_hash)
- Sessao PHP com `$_SESSION['admin_id']` e `$_SESSION['admin_nome']`
- Funcao `requireAdmin()` redireciona para login.php se nao autenticado

---

## Upload de Imagens

- Diretorio: `uploads/insetos/`
- Tipos aceitos: JPG, PNG, WebP
- Tamanho maximo: 5MB
- Nome gerado com prefixo + uniqid()

---

## Observacoes Tecnicas

- PHP com PDO e prepared statements em todas as queries
- Banco MariaDB/MySQL com charset utf8mb4
- Frontend vanilla JS sem frameworks
- Fontes: Playfair Display (titulos) e Source Sans 3 (texto)
- Design responsivo com media queries para mobile


