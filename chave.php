<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Dicotômica - ENT107 UFLA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="index.php?inline_css=1">
  <style>
    
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --verde: #3a6b35;
      --verde-escuro: #2c5228;
      --verde-claro: #5a9053;
      --verde-bg: #f0f5ef;
      --verde-borda: #c8dcc6;
      --texto: #1a2e18;
      --texto-suave: #4a6648;
      --branco: #ffffff;
      --sombra: 0 4px 24px rgba(42, 82, 40, 0.10);
    }

    body {
      font-family: 'Source Sans 3', sans-serif;
      background: var(--verde-bg);
      color: var(--texto);
      min-height: 100vh;
    }

    header {
      background: var(--verde);
      padding: 16px 32px;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .header-back {
      color: var(--branco);
      text-decoration: none;
      font-size: 1.1rem;
      line-height: 1;
      opacity: 0.8;
      transition: opacity 0.15s;
    }

    .header-back:hover {
      opacity: 1;
    }

    .header-info {
      flex: 1;
    }

    .header-info h2 {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-weight: 400;
      color: var(--branco);
      font-size: 1.1rem;
      line-height: 1.3;
    }

    .header-info p {
      color: rgba(255, 255, 255, 0.70);
      font-size: 0.84rem;
      margin-top: 2px;
    }

    .header-passo {
      color: rgba(255, 255, 255, 0.70);
      font-size: 0.88rem;
      white-space: nowrap;
    }

    .chave-main {
      max-width: 700px;
      margin: 0 auto;
      padding: 32px 20px 80px;
    }

    .progress-bar {
      width: 100%;
      height: 6px;
      background: var(--verde-borda);
      border-radius: 3px;
      overflow: hidden;
      margin-bottom: 32px;
    }

    .progress-fill {
      height: 100%;
      background: var(--verde);
      border-radius: 3px;
      transition: width 0.4s ease;
    }

    /* PASSO CARD */
    .passo-card {
      background: var(--branco);
      border: 1px solid var(--verde-borda);
      border-radius: 16px;
      padding: 32px;
      box-shadow: var(--sombra);
      animation: fadeSlide 0.3s ease;
    }

    @keyframes fadeSlide {
      from {
        opacity: 0;
        transform: translateX(20px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .passo-num {
      width: 40px;
      height: 40px;
      background: var(--verde);
      color: var(--branco);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .passo-pergunta {
      font-size: 1.1rem;
      line-height: 1.65;
      color: var(--texto);
      margin-bottom: 28px;
    }

    .opcao-btn {
      width: 100%;
      background: var(--branco);
      border: 1.5px solid var(--verde-borda);
      border-radius: 12px;
      padding: 18px 22px;
      cursor: pointer;
      text-align: left;
      margin-bottom: 12px;
      transition: all 0.18s;
      display: flex;
      align-items: flex-start;
      gap: 14px;
      font-family: 'Source Sans 3', sans-serif;
    }

    .opcao-btn:hover {
      border-color: var(--verde);
      background: var(--verde-bg);
      transform: translateY(-1px);
    }

    .opcao-badge {
      width: 30px;
      height: 30px;
      min-width: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 700;
    }

    .badge-sim {
      background: var(--verde);
      color: var(--branco);
    }

    .badge-nao {
      background: #c0392b;
      color: var(--branco);
    }

    .opcao-label {
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--texto);
    }

    .opcao-desc {
      font-size: 0.9rem;
      color: var(--texto-suave);
      margin-top: 4px;
      line-height: 1.4;
    }

    
    .resultado-card {
      background: var(--branco);
      border: 2px solid var(--verde);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: var(--sombra);
      animation: fadeSlide 0.3s ease;
    }

    .resultado-topo {
      padding: 32px 32px 20px;
      text-align: center;
    }

    .resultado-check {
      width: 70px;
      height: 70px;
      background: var(--verde);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 20px;
    }

    .resultado-label {
      color: var(--verde);
      font-size: 0.88rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    .resultado-familia {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 2rem;
      color: var(--verde-escuro);
      margin: 8px 0 4px;
    }

    .resultado-ordem {
      color: var(--texto-suave);
      font-size: 0.92rem;
    }

    .resultado-img {
      width: 100%;
      height: 240px;
      object-fit: cover;
      display: block;
    }

    .resultado-img-placeholder {
      width: 100%;
      height: 240px;
      background: linear-gradient(135deg, #d4e8d0 0%, #b8d9b3 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem;
    }

    .resultado-desc {
      padding: 24px 32px;
      font-size: 0.97rem;
      line-height: 1.7;
      color: var(--texto);
    }

    .resultado-acoes {
      padding: 0 32px 32px;
      display: flex;
      gap: 14px;
    }

    .btn {
      flex: 1;
      padding: 13px 0;
      border-radius: 10px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.18s;
      text-decoration: none;
      text-align: center;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .btn-outline {
      background: var(--branco);
      border: 1.5px solid var(--verde);
      color: var(--verde);
    }

    .btn-outline:hover {
      background: var(--verde-bg);
    }

    .btn-solid {
      background: var(--verde);
      color: var(--branco);
    }

    .btn-solid:hover {
      background: var(--verde-escuro);
    }

    /* Estado: sem chave cadastrada */
    .sem-chave {
      background: var(--branco);
      border-radius: 16px;
      padding: 48px 32px;
      text-align: center;
      border: 1px solid var(--verde-borda);
    }

    .sem-chave h3 {
      color: var(--verde-escuro);
      font-size: 1.1rem;
      margin-bottom: 10px;
    }

    .sem-chave p {
      color: var(--texto-suave);
      line-height: 1.6;
    }

    @media (max-width: 600px) {
      .chave-main {
        padding: 20px 12px 60px;
      }

      .passo-card {
        padding: 22px 18px;
      }

      .resultado-acoes {
        flex-direction: column;
      }

      header {
        padding: 14px 16px;
      }
    }
  </style>
</head>

<body>

  <header>
    <a class="header-back" href="index.php">←</a>
    <div class="header-info">
      <h2 id="headerTitulo">Carregando.</h2>
      <p>Chave Dicotômica</p>
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
      document.title = ordemNome + ' – ENT107';

      
      const rp = await fetch(`api.php?action=passos&ordem_id=${ordemId}`);
      passos = await rp.json();

      if (!passos || passos.length === 0) {
        document.getElementById('conteudo').innerHTML = `
      <div class="sem-chave">
        <h3>Chave dicotômica não cadastrada</h3>
        <p>A chave para <em>${ordemNome}</em> ainda não foi inserida pelo administrador.<br>Entre em contato com o responsável pela disciplina.</p>
        <a href="index.php" class="btn btn-solid" style="display:inline-flex;margin-top:20px;max-width:240px">← Voltar ao Início</a>
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
        <span class="opcao-badge badge-sim">✓</span>
        <div>
          <div class="opcao-label">SIM</div>
          ${p.opcao_sim_texto ? `<div class="opcao-desc">${p.opcao_sim_texto}</div>` : ''}
        </div>
      </button>
      <button class="opcao-btn" onclick="responder('nao', ${idx})">
        <span class="opcao-badge badge-nao">✗</span>
        <div>
          <div class="opcao-label">NÃO</div>
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
        `<div class="resultado-img-placeholder">🪲</div>`;

      document.getElementById('conteudo').innerHTML = `
    <div class="resultado-card">
      <div class="resultado-topo">
        <div class="resultado-check">✓</div>
        <div class="resultado-label">Família Identificada!</div>
        <div class="resultado-familia">${familia.nome || '—'}</div>
        <div class="resultado-ordem">Ordem: <em>${ordemNome}</em></div>
      </div>
      ${imgHtml}
      ${familia.descricao || familia.exemplos ? `
        <div class="resultado-desc">
          ${familia.descricao ? `<p>${familia.descricao}</p>` : ''}
          ${familia.exemplos ? `<p style="margin-top:10px;color:var(--texto-suave)"><strong>Exemplos:</strong> ${familia.exemplos}</p>` : ''}
        </div>` : ''}
      <div class="resultado-acoes">
        <button class="btn btn-outline" onclick="renderPasso(0)">↺ Refazer Identificação</button>
        <a class="btn btn-solid" href="index.php">⌂ Voltar ao Início</a>
      </div>
    </div>`;
    }

    init();
  </script>
</body>

</html>