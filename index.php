<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Entomológica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/site-home.css">
</head>

<body>

  <header>
  <h1>Chave de Classificação para Algumas Ordens e Famílias da Classe Insecta</h1>
  <p>IF GOIANO &nbsp;- &nbsp;Entomologia de Insetos</p>
  <div class="header-accent"></div>
  <a href="admin/check_auth.php" style="
    position: absolute;
    top: 16px;
    right: 20px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 8px;
    padding: 7px 16px;
    font-size: 0.85rem;
    font-family: 'Source Sans 3', sans-serif;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.18s;
  " onmouseover="this.style.background='rgba(255,255,255,0.25)'"
     onmouseout="this.style.background='rgba(255,255,255,0.15)'">
    Admin
  </a>
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

