# Relatorio de Alteracoes - Interface, Responsividade e Acessibilidade

## Visao Geral

Foram realizadas melhorias na interface publica, na chave dicotomica e no painel administrativo do sistema. O objetivo foi tornar a navegacao mais clara, melhorar a apresentacao em telas pequenas e corrigir pontos de acessibilidade observados durante os testes.

As alteracoes mantiveram a estrutura atual do projeto e o funcionamento da API publica.

---

## Arquivos Criados

| arquivo | descricao |
|---------|-----------|
| `assets/css/ui-base.css` | estilos compartilhados de foco visivel e reducao de movimento |
| `assets/css/admin-responsive.css` | comportamento responsivo das telas administrativas |
| `assets/js/admin-layout.js` | controle do menu mobile e adaptacao das tabelas do admin |
| `docs/RELATORIO_IMPLEMENTACAO_UI_ACESSIBILIDADE.md` | descricao dos blocos implementados e testes realizados |
| `docs/evidencias/` | capturas das telas verificadas durante os testes |

---

## Area Publica

### Pagina Principal (`index.php`)

- Foi incluida uma orientacao inicial para informar ao aluno como iniciar a identificacao.
- Os botoes dos cards foram renomeados para `Ver caracteristicas` e `Iniciar identificacao`.
- O acesso a area administrativa foi ajustado para nao sobrepor o titulo em telas de celular.
- O botao de ajuda foi removido, pois ainda nao possuia funcao implementada.
- Registros sem imagem agora exibem o texto `Imagem nao cadastrada` em um componente visual discreto.

### Modal de Detalhes

- O modal recebeu estrutura adequada de dialogo, com titulo associado.
- O fechamento pode ser feito pelo botao, pelo fundo da tela ou pela tecla `Escape`.
- Ao abrir o modal, o foco e direcionado para o conteudo.
- Ao fechar, o foco retorna ao botao que abriu os detalhes.

---

## Chave Dicotomica (`chave.php`)

### Navegacao

- A tela passou a exibir progresso textual junto com a barra de progresso.
- Foi adicionado o painel `Historico da identificacao`, que mostra as escolhas feitas durante a identificacao atual.
- Foi incluido o botao `Voltar ao passo anterior`, respeitando o caminho realmente percorrido pelo aluno.
- Ao selecionar uma alternativa, as duas opcoes ficam bloqueadas durante a transicao para evitar cliques repetidos.
- O historico e apagado ao refazer a identificacao ou recarregar a pagina.

### Apresentacao

- O texto `Specimen Match` foi substituido por `Chave dicotomica`.
- As alternativas foram reorganizadas para ocupar menos espaco no desktop.
- Em celulares, as opcoes passaram a ser apresentadas em cards compactos, com imagem, descricao e botao no mesmo bloco.
- Imagens ausentes passaram a usar o mesmo padrao visual adotado na pagina principal.

### Acessibilidade

- A barra de progresso utiliza `role="progressbar"` e atributos ARIA atualizados a cada passo.
- Foi adicionada uma regiao de aviso para informar passo atual e familia identificada.
- Os botoes possuem nomes diferentes para cada alternativa, incluindo a descricao da opcao.
- O foco e direcionado para a pergunta ao avancar ou voltar e para o resultado ao concluir.
- Os controles principais possuem area de clique adequada para uso em telas sensiveis ao toque.

---

## Area Administrativa

### Autenticacao

- O redirecionamento de paginas protegidas foi corrigido para funcionar no caminho atual do projeto.
- O formulario de login recebeu rotulos associados aos campos e configuracao de preenchimento automatico.
- Foi removido o processamento duplicado do login na mesma pagina.

### Layout Responsivo

- A barra lateral continua fixa no desktop.
- Em telas menores, a navegacao passou a funcionar como menu lateral recolhivel.
- O menu possui botao de abertura, sobreposicao de fundo e fechamento pela tecla `Escape`.
- O foco retorna ao botao `Menu` depois do fechamento por teclado.
- As tabelas passam a ser exibidas como cards rotulados em celulares.
- Formularios, filtros e seletores passam a ocupar uma unica coluna em telas pequenas.

As telas adaptadas foram:

- Dashboard;
- Ordens;
- Familias;
- Chaves Dicotomicas;
- Administradores.

### Imagens Pendentes

- O dashboard agora apresenta contadores de itens sem imagem cadastrada.
- As listagens de ordens e familias exibem badge `Sem imagem`.
- O editor de chaves identifica alternativas sem imagem.
- Os formularios apresentam um estado vazio para pre-visualizacao quando ainda nao existe imagem cadastrada.

Contagens verificadas no banco durante os testes:

| item | quantidade sem imagem |
|------|----------------------:|
| Ordens | 12 |
| Familias | 69 |
| Alternativas da chave | 8 |

---

## Arquivos Alterados

| arquivo | alteracao principal |
|---------|--------------------|
| `index.php` | orientacao inicial, modal acessivel e novos estados sem imagem |
| `chave.php` | progresso acessivel, historico, retorno e bloqueio de selecao |
| `includes/db.php` | correcao do redirecionamento administrativo |
| `admin/login.php` | melhoria do formulario de acesso |
| `admin/index.php` | indicadores de imagens pendentes e suporte ao layout responsivo |
| `admin/ordens.php` | badges, preview e responsividade |
| `admin/familias.php` | badges, preview e responsividade |
| `admin/chaves.php` | indicadores das alternativas, preview e responsividade |
| `admin/admins.php` | responsividade das tabelas e formularios |
| `assets/css/site-home.css` | acabamento e adaptacao da pagina principal |
| `assets/css/site-chave.css` | layout e responsividade da chave |

---

## Compatibilidade

- A API publica em `api.php` manteve as mesmas actions e o mesmo formato de resposta.
- O historico da chave e mantido somente na memoria da pagina e nao e gravado no banco.
- Nao foram adicionadas imagens de conteudo; os registros pendentes continuam disponiveis para preenchimento posterior.
- O fluxo de upload existente para ordens, familias e alternativas foi preservado.

---

## Testes Realizados

- Verificacao de sintaxe dos arquivos PHP alterados.
- Acesso a pagina administrativa protegida sem sessao e validacao do redirecionamento para o login.
- Login, acesso ao dashboard e logout.
- Abertura e fechamento do modal da pagina principal.
- Verificacao visual da pagina principal em desktop e celular.
- Percurso completo na chave `Hemiptera-Auchenorrhyncha`, com conclusao em `Cicadellidae`.
- Testes de voltar passo, refazer identificacao e recarregar a pagina sem manter historico.
- Verificacao da chave em desktop e celular, sem rolagem horizontal.
- Verificacao das telas administrativas em desktop, tablet e celular.
- Teste do menu administrativo mobile por botao, fundo da tela e tecla `Escape`.
- Comparacao dos indicadores de imagens pendentes com consultas diretas ao banco.
- Verificacao de erros no navegador durante os fluxos testados.

---

## Evidencias

As capturas realizadas durante os testes estao na pasta `docs/evidencias/`.

| tela | arquivos |
|------|----------|
| Pagina principal | `home-desktop.png`, `home-modal-desktop.png`, `home-mobile.png` |
| Chave dicotomica | `chave-desktop.png`, `chave-resultado-desktop.png`, `chave-mobile.png`, `chave-mobile-historico.png` |
| Administracao | `admin-login-desktop.png`, `admin-dashboard-desktop.png`, `admin-dashboard-mobile.png`, `admin-drawer-mobile.png`, `admin-ordens-mobile.png`, `admin-chaves-mobile.png` |

---

## Mapeamento do Sistema com Graphify

Foi gerado um grafo do projeto utilizando a ferramenta Graphify, que pode ser acessado na pasta `graphify-out`. Esse grafo serve para mapear as dependencias, as comunidades logicas e o fluxo de dados entre os arquivos e componentes do sistema, facilitando a compreensao visual da arquitetura do projeto.

!Grafo gerado pelo Graphify

---

## Proximas Melhorias

### Evitar hard delete em dados principais

Ordens e familias podem ser excluidas definitivamente, com cascata. Sugestoes:

- Preferir inativacao ou soft delete para dados usados em contexto academico.
- Exibir impacto antes de excluir, como quantidade de familias e passos afetados.
- Manter trilha de auditoria para acoes administrativas.
- Impedir exclusao acidental de dados usados em chaves publicadas.
