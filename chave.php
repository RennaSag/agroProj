<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave DicotÃ´mica </title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/site-chave.css">
</head>

<body>

  <header>
    <a class="header-back" href="index.php"></a>
    <div class="header-info">
      <h2 id="headerTitulo">Carregando.</h2>
      <p>Chave DicotÃ´mica</p>
    </div>
    <span class="header-passo" id="headerPasso"></span>
  </header>

  <div class="chave-main">
    <div class="progress-bar">
      <div class="progress-fill" id="progressFill" style="width:0%"></div>
    </div>
    <div id="conteudo">
      <div style="text-align:center;padding:40px;color:var(--texto-suave)">Carregando.</div>
    </div>
  </div>

  <script>
    const params = new URLSearchParams(location.search);
    const ordemId = params.get('ordem');
    let passos = [];
    let currentIndex = 0;
    let ordemNome = '';

    async function init() {
      if (!ordemId) {
        location.href = 'index.php';
        return;
      }

      
      const ro = await fetch(`api.php?action=ordem&id=${ordemId}`);
      const ordem = await ro.json();
      ordemNome = ordem.nome;
      document.getElementById('headerTitulo').textContent = ordemNome;
      document.title = ordemNome;

      
      const rp = await fetch(`api.php?action=passos&ordem_id=${ordemId}`);
      passos = await rp.json();

      if (!passos || passos.length === 0) {
        document.getElementById('conteudo').innerHTML = `
      <div class="sem-chave">
        <h3>Chave dicotÃ´mica nÃ£o cadastrada</h3>
        <p>A chave para <em>${ordemNome}</em> ainda nÃ£o foi inserida pelo administrador.<br>Entre em contato com o responsÃ¡vel pela disciplina.</p>
        <a href="index.php" class="btn btn-solid" style="display:inline-flex;margin-top:20px;max-width:240px">Voltar ao InÃ­cio</a>
      </div>`;
        return;
      }

      renderPasso(0);
    }

    function renderPasso(idx) {
      currentIndex = idx;
      const p = passos[idx];
      const total = p.total_passos;
      const progresso = Math.round((idx / total) * 100);

      document.getElementById('progressFill').style.width = progresso + '%';
      document.getElementById('headerPasso').textContent = `Passo ${idx + 1} de ${total}`;

      document.getElementById('conteudo').innerHTML = `
    <div class="passo-card">
      <div class="passo-num">${p.passo_numero}.</div>
      <p class="passo-pergunta">${p.pergunta}</p>
      <button class="opcao-btn" onclick="responder('sim', ${idx})">
        <span class="opcao-badge badge-sim">âœ“</span>
        <div>
          <div class="opcao-label">SIM</div>
          ${p.opcao_sim_texto ? `<div class="opcao-desc">${p.opcao_sim_texto}</div>` : ''}
        </div>
      </button>
      <button class="opcao-btn" onclick="responder('nao', ${idx})">
        <span class="opcao-badge badge-nao">âœ—</span>
        <div>
          <div class="opcao-label">NÃƒO</div>
          ${p.opcao_nao_texto ? `<div class="opcao-desc">${p.opcao_nao_texto}</div>` : ''}
        </div>
      </button>
    </div>`;
    }

    function responder(escolha, idx) {
      const p = passos[idx];

      if (escolha === 'sim') {
        if (p.sim_resultado_familia_id) {
          renderResultado({
            nome: p.sim_familia,
            descricao: p.sim_desc,
            exemplos: p.sim_ex,
            imagem: p.sim_img
          });
        } else if (p.sim_leva_passo) {
          const nextIdx = passos.findIndex(x => x.passo_numero == p.sim_leva_passo);
          if (nextIdx >= 0) renderPasso(nextIdx);
        }
      } else {
        if (p.nao_resultado_familia_id) {
          renderResultado({
            nome: p.nao_familia,
            descricao: p.nao_desc,
            exemplos: p.nao_ex,
            imagem: p.nao_img
          });
        } else if (p.nao_leva_passo) {
          const nextIdx = passos.findIndex(x => x.passo_numero == p.nao_leva_passo);
          if (nextIdx >= 0) renderPasso(nextIdx);
        }
      }
    }

    function renderResultado(familia) {
      document.getElementById('progressFill').style.width = '100%';
      document.getElementById('headerPasso').textContent = 'Identificado!';

      const imgHtml = familia.imagem ?
        `<img src="${familia.imagem}" class="resultado-img" alt="${familia.nome}">` :
        `<div class="resultado-img-placeholder">ImagemAqui</div>`;

      document.getElementById('conteudo').innerHTML = `
    <div class="resultado-card">
      <div class="resultado-topo">
        <div class="resultado-check">âœ“</div>
        <div class="resultado-label">FamÃ­lia Identificada!</div>
        <div class="resultado-familia">${familia.nome || 'â€”'}</div>
        <div class="resultado-ordem">Ordem: <em>${ordemNome}</em></div>
      </div>
      ${imgHtml}
      ${familia.descricao || familia.exemplos ? `
        <div class="resultado-desc">
          ${familia.descricao ? `<p>${familia.descricao}</p>` : ''}
          ${familia.exemplos ? `<p style="margin-top:10px;color:var(--texto-suave)"><strong>Exemplos:</strong> ${familia.exemplos}</p>` : ''}
        </div>` : ''}
      <div class="resultado-acoes">
        <button class="btn btn-outline" onclick="renderPasso(0)">Refazer IdentificaÃ§Ã£o</button>
        <a class="btn btn-solid" href="index.php">Voltar ao InÃ­cio</a>
      </div>
    </div>`;
    }

    init();
  </script>
</body>

</html>
