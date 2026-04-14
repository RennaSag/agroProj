<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Entomológica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
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
      background: var(--branco);
      color: var(--texto);
      min-height: 100vh;
    }

    
    header {
      background: var(--verde);
      padding: 28px 40px 24px;
      text-align: center;
      position: relative;
    }

    header h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.3rem, 3vw, 1.9rem);
      color: var(--branco);
      font-weight: 700;
      letter-spacing: -0.01em;
      line-height: 1.3;
    }

    header p {
      color: rgba(255, 255, 255, 0.80);
      font-size: 0.92rem;
      margin-top: 6px;
      letter-spacing: 0.08em;
      font-weight: 300;
    }

    .header-accent {
      width: 40px;
      height: 3px;
      background: rgba(255, 255, 255, 0.40);
      margin: 14px auto 0;
      border-radius: 2px;
    }

    
    main {
      max-width: 1240px;
      margin: 0 auto;
      padding: 48px 32px 80px;
    }

    .ordens-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 28px;
    }

  
    .card {
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid var(--verde-borda);
      background: var(--branco);
      box-shadow: var(--sombra);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: flex;
      flex-direction: column;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(42, 82, 40, 0.16);
    }

    .card-img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      background: var(--verde-bg);
      display: block;
    }

    .card-img-placeholder {
      width: 100%;
      height: 220px;
      background: linear-gradient(135deg, #d4e8d0 0%, #b8d9b3 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--verde-claro);
      font-size: 3rem;
    }

    .card-body {
      padding: 20px 24px 24px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .card-nome {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 1.15rem;
      color: var(--verde-escuro);
      text-align: center;
      font-weight: 400;
    }

    .card-acoes {
      display: flex;
      gap: 10px;
      margin-top: auto;
    }

    .btn {
      flex: 1;
      padding: 10px 0;
      border-radius: 8px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.18s ease;
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

    /* MODAL */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .modal-overlay.open {
      display: flex;
    }

    .modal {
      background: var(--branco);
      border-radius: 18px;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.28);
      animation: slideUp 0.25s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal-header {
      background: var(--verde);
      color: var(--branco);
      padding: 18px 24px;
      border-radius: 18px 18px 0 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1;
    }

    .modal-header h3 {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-weight: 400;
      font-size: 1.1rem;
    }

    .modal-close {
      background: none;
      border: none;
      color: var(--branco);
      font-size: 1.3rem;
      cursor: pointer;
      line-height: 1;
      opacity: 0.8;
      transition: opacity 0.15s;
    }

    .modal-close:hover {
      opacity: 1;
    }

    .modal-body {
      padding: 24px;
    }

    .modal-img {
      width: 100%;
      border-radius: 10px;
      height: 220px;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .modal-section-title {
      font-family: 'Source Sans 3', sans-serif;
      font-weight: 600;
      color: var(--verde);
      font-size: 0.88rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 8px;
      margin-top: 20px;
    }

    .modal-section-title:first-child {
      margin-top: 0;
    }

    .modal-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .modal-list li {
      padding-left: 16px;
      position: relative;
      color: var(--texto);
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .modal-list li::before {
      content: '•';
      position: absolute;
      left: 0;
      color: var(--verde-claro);
    }

    .modal-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 6px;
    }

    .tag {
      background: var(--verde-bg);
      border: 1px solid var(--verde-borda);
      color: var(--verde-escuro);
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 0.84rem;
      font-style: italic;
    }

    .modal-footer {
      padding: 0 24px 24px;
    }

    .btn-acessar-chave {
      display: block;
      width: 100%;
      padding: 14px;
      background: var(--verde);
      color: var(--branco);
      border: none;
      border-radius: 10px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: background 0.18s;
    }

    .btn-acessar-chave:hover {
      background: var(--verde-escuro);
    }

    
    .fab-help {
      position: fixed;
      bottom: 28px;
      right: 28px;
      width: 44px;
      height: 44px;
      background: var(--verde);
      color: var(--branco);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      font-weight: 700;
      box-shadow: 0 4px 16px rgba(42, 82, 40, 0.30);
      cursor: pointer;
      text-decoration: none;
      border: none;
    }

    
    .chave-container {
      max-width: 700px;
      margin: 0 auto;
      padding: 40px 24px 80px;
    }

    .chave-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 12px;
    }

    .btn-back {
      background: none;
      border: none;
      cursor: pointer;
      color: var(--verde);
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      gap: 6px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.95rem;
      font-weight: 600;
      text-decoration: none;
      padding: 0;
    }

    .chave-titulo {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-weight: 400;
      color: var(--verde-escuro);
      font-size: 1.15rem;
      flex: 1;
    }

    .chave-passo-info {
      color: var(--texto-suave);
      font-size: 0.88rem;
      margin-left: auto;
      white-space: nowrap;
    }

    .progress-bar {
      width: 100%;
      height: 5px;
      background: var(--verde-bg);
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

    .passo-card {
      background: var(--branco);
      border: 1px solid var(--verde-borda);
      border-radius: 14px;
      padding: 28px;
      box-shadow: var(--sombra);
    }

    .passo-num {
      width: 36px;
      height: 36px;
      background: var(--verde);
      color: var(--branco);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
      font-weight: 600;
      margin-bottom: 16px;
    }

    .passo-pergunta {
      font-size: 1.05rem;
      line-height: 1.6;
      color: var(--texto);
      margin-bottom: 24px;
    }

    .opcao-btn {
      width: 100%;
      background: var(--branco);
      border: 1.5px solid var(--verde-borda);
      border-radius: 10px;
      padding: 16px 20px;
      cursor: pointer;
      text-align: left;
      margin-bottom: 12px;
      transition: all 0.18s;
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }

    .opcao-btn:hover {
      border-color: var(--verde);
      background: var(--verde-bg);
    }

    .opcao-badge {
      width: 28px;
      height: 28px;
      min-width: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: 700;
    }

    .badge-sim {
      background: var(--verde);
      color: var(--branco);
    }

    .badge-nao {
      background: #d44;
      color: var(--branco);
    }

    .opcao-label {
      font-weight: 600;
      font-size: 0.92rem;
      color: var(--texto);
    }

    .opcao-desc {
      font-size: 0.88rem;
      color: var(--texto-suave);
      margin-top: 3px;
    }

    
    .resultado-card {
      background: var(--branco);
      border: 2px solid var(--verde);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--sombra);
      text-align: center;
    }

    .resultado-check {
      width: 64px;
      height: 64px;
      background: var(--verde);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      margin: 32px auto 20px;
    }

    .resultado-titulo {
      color: var(--verde);
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    .resultado-familia {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 1.8rem;
      color: var(--verde-escuro);
      margin: 8px 0 4px;
    }

    .resultado-ordem {
      color: var(--texto-suave);
      font-size: 0.92rem;
      margin-bottom: 20px;
    }

    .resultado-img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
    }

    .resultado-desc {
      padding: 20px 28px;
      font-size: 0.95rem;
      color: var(--texto);
      line-height: 1.6;
      text-align: left;
    }

    .resultado-acoes {
      padding: 0 28px 28px;
      display: flex;
      gap: 12px;
    }

    @media (max-width: 600px) {
      header {
        padding: 20px 20px 16px;
      }

      main {
        padding: 28px 16px 60px;
      }

      .ordens-grid {
        grid-template-columns: 1fr;
        gap: 18px;
      }

      .chave-container {
        padding: 24px 16px 60px;
      }
    }
  </style>
</head>

<body>

  <header>
    <h1>Chave de Classificação para Algumas Ordens e Famílias da Classe Insecta</h1>
    <p>IF GOIANO &nbsp;- &nbsp;Entomologia de Insetos</p>
    <div class="header-accent"></div>
  </header>

  <main>
    <div class="ordens-grid" id="ordensgrid">
    </div>
  </main>

  
  <div class="modal-overlay" id="modalOverlay" onclick="if(event.target===this)fecharModal()">
    <div class="modal" id="modalContent">
      <div class="modal-header">
        <h3 id="modalTitulo">-</h3>
        <button class="modal-close" onclick="fecharModal()">✕</button>
      </div>
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer">
        <a href="#" class="btn-acessar-chave" id="modalChaveBtn">Acessar Chave</a>
      </div>
    </div>
  </div>

  <button class="fab-help" title="Ajuda">?</button>

  <script>
    // carrega ordens via API
    async function carregarOrdens() {
      const resp = await fetch('api.php?action=ordens');
      const data = await resp.json();
      const grid = document.getElementById('ordensgrid');
      grid.innerHTML = '';

      data.forEach(ordem => {
        const card = document.createElement('div');
        card.className = 'card';

        const imgHtml = ordem.imagem ?
          `<img src="${ordem.imagem}" class="card-img" alt="${ordem.nome}" loading="lazy">` :
          `<div class="card-img-placeholder">ImagemAqui</div>`;

        card.innerHTML = `
      ${imgHtml}
      <div class="card-body">
        <div class="card-nome">${ordem.nome}</div>
        <div class="card-acoes">
          <button class="btn btn-outline" onclick="abrirModal(${ordem.id})">Descrição</button>
          <a class="btn btn-solid" href="chave.php?ordem=${ordem.id}">Chave</a>
        </div>
      </div>`;
        grid.appendChild(card);
      });
    }

    async function abrirModal(ordemId) {
      const resp = await fetch(`api.php?action=ordem&id=${ordemId}`);
      const d = await resp.json();

      document.getElementById('modalTitulo').textContent = d.nome;
      document.getElementById('modalChaveBtn').href = `chave.php?ordem=${d.id}`;

      const caract = d.caracteristicas ? JSON.parse(d.caracteristicas) : [];
      const caracterHtml = caract.length ? `
    <div class="modal-section-title">Características Gerais</div>
    <ul class="modal-list">${caract.map(c => `<li>${c}</li>`).join('')}</ul>
  ` : '';

      const exemplosHtml = d.exemplos ? `
    <div class="modal-section-title">Exemplos</div>
    <p style="color:var(--texto);font-size:0.95rem">${d.exemplos}</p>
  ` : '';

      const agricolaHtml = d.importancia_agricola ? `
    <div class="modal-section-title">Importância Agrícola</div>
    <p style="color:var(--texto);font-size:0.95rem;line-height:1.6">${d.importancia_agricola}</p>
  ` : '';

      const familiasHtml = d.familias && d.familias.length ? `
    <div class="modal-section-title">Famílias Incluídas</div>
    <div class="modal-tags">${d.familias.map(f => `<span class="tag">${f}</span>`).join('')}</div>
  ` : '';

      const imgHtml = d.imagem ? `<img src="${d.imagem}" class="modal-img" alt="${d.nome}">` : '';

      document.getElementById('modalBody').innerHTML = imgHtml + caracterHtml + exemplosHtml + agricolaHtml + familiasHtml;
      document.getElementById('modalOverlay').classList.add('open');
    }

    function fecharModal() {
      document.getElementById('modalOverlay').classList.remove('open');
    }

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') fecharModal();
    });
    carregarOrdens();
  </script>
</body>

</html>