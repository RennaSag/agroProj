<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Dicotômica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/site-chave.css?v=specimen-match-2">
</head>

<body>

  <header>
    <a class="header-back" href="index.php" aria-label="Voltar">&#8592;</a>
    <div class="header-info">
      <h2 id="headerTitulo">Carregando.</h2>
      <p>Specimen Match</p>
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

    function escapeHtml(value) {
      return String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      }[char]));
    }

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
        <h3>Chave dicotômica não cadastrada</h3>
        <p>A chave para <em>${escapeHtml(ordemNome)}</em> ainda não foi inserida pelo administrador.<br>Entre em contato com o responsável pela disciplina.</p>
        <a href="index.php" class="btn btn-solid" style="display:inline-flex;margin-top:20px;max-width:240px">Voltar ao Início</a>
      </div>`;
        return;
      }

      renderPasso(0);
    }

    function renderPasso(idx) {
      currentIndex = idx;
      const p = passos[idx];
      const total = p.total_passos;
      const progresso = Math.round(((idx + 1) / total) * 100);

      document.getElementById('progressFill').style.width = progresso + '%';
      document.getElementById('headerPasso').textContent = `Passo ${idx + 1} de ${total}`;

      document.getElementById('conteudo').innerHTML = `
    <section class="specimen-step">
      <p class="passo-pergunta">${escapeHtml(p.pergunta)}</p>
      <div class="specimen-compare">
        ${renderOpcao('sim', idx, 'OPÇÃO A', p.opcao_sim_texto, p.sim_imagem)}
        <div class="compare-divider" aria-hidden="true">OU</div>
        ${renderOpcao('nao', idx, 'OPÇÃO B', p.opcao_nao_texto, p.nao_imagem)}
      </div>
    </section>`;
    }

    function renderOpcao(escolha, idx, label, texto, imagem) {
      const titulo = texto || (escolha === 'sim' ? 'Característica presente' : 'Característica ausente');
      const imagemHtml = imagem ?
        `<img src="${escapeHtml(imagem)}" class="specimen-img" alt="${escapeHtml(titulo)}">` :
        `<div class="specimen-img-placeholder"><span>${label.slice(-1)}</span></div>`;

      return `
        <article class="specimen-option">
          <div class="option-kicker">${label}</div>
          <div class="specimen-media">${imagemHtml}</div>
          <div class="specimen-copy">
            <h3>${escapeHtml(titulo)}</h3>
            ${texto ? `<p>${escapeHtml(texto)}</p>` : '<p>Compare o espécime com esta alternativa.</p>'}
          </div>
          <button class="select-option" onclick="responder('${escolha}', ${idx}, this)">Selecionar</button>
        </article>`;
    }

    function responder(escolha, idx, btn = null) {
      if (btn) {
        btn.closest('.specimen-option').classList.add('selected');
      }

      const p = passos[idx];

      setTimeout(() => {
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
      }, btn ? 180 : 0);
    }

    function renderResultado(familia) {
      document.getElementById('progressFill').style.width = '100%';
      document.getElementById('headerPasso').textContent = 'Identificado!';

      const imgHtml = familia.imagem ?
        `<img src="${escapeHtml(familia.imagem)}" class="resultado-img" alt="${escapeHtml(familia.nome)}">` :
        `<div class="resultado-img-placeholder">ImagemAqui</div>`;

      document.getElementById('conteudo').innerHTML = `
    <div class="resultado-card">
      <div class="resultado-topo">
        <div class="resultado-check">&#10003;</div>
        <div class="resultado-label">Família Identificada</div>
        <div class="resultado-familia">${escapeHtml(familia.nome || '-')}</div>
        <div class="resultado-ordem">Ordem: <em>${escapeHtml(ordemNome)}</em></div>
      </div>
      ${imgHtml}
      ${familia.descricao || familia.exemplos ? `
        <div class="resultado-desc">
          ${familia.descricao ? `<p>${escapeHtml(familia.descricao)}</p>` : ''}
          ${familia.exemplos ? `<p style="margin-top:10px;color:var(--texto-suave)"><strong>Exemplos:</strong> ${escapeHtml(familia.exemplos)}</p>` : ''}
        </div>` : ''}
      <div class="resultado-acoes">
        <button class="btn btn-outline" onclick="renderPasso(0)">Refazer Identificação</button>
        <a class="btn btn-solid" href="index.php">Voltar ao Início</a>
      </div>
    </div>`;
    }

    init();
  </script>
</body>

</html>
