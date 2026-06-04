<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Entomológica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="assets/css/site-home.css?v=20260527">
</head>

<body>

  <header>
  <h1>Chaves de Classificação Entomólica</h1>
  <p>IF GOIANO &nbsp;- &nbsp;Entomologia de Insetos</p>
  <div class="header-accent"></div>
  <a href="admin/check_auth.php" class="admin-link">Área administrativa</a>
</header>

  <main>
    <section class="intro" aria-labelledby="introTitulo">
      <p class="intro-kicker">Identificação orientada</p>
      <h2 id="introTitulo">Selecione uma ordem para começar</h2>
      <p>Consulte as características principais ou inicie a chave dicotômica para identificar a família do inseto.</p>
    </section>
    <div class="ordens-grid" id="ordensgrid">
    </div>
  </main>

  <div class="modal-overlay" id="modalOverlay" aria-hidden="true">
    <div class="modal" id="modalContent" role="dialog" aria-modal="true" aria-labelledby="modalTitulo" tabindex="-1">
      <div class="modal-header">
        <h3 id="modalTitulo">-</h3>
        <button type="button" class="modal-close" id="modalClose" aria-label="Fechar detalhes">×</button>
      </div>
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer">
        <a href="#" class="btn-acessar-chave" id="modalChaveBtn">Iniciar identificação</a>
      </div>
    </div>
  </div>

  <script>
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    let modalTrigger = null;

    function escapeHtml(value) {
      const node = document.createElement('span');
      node.textContent = String(value ?? '');
      return node.innerHTML;
    }

    function imagemAusente(className) {
      return `<div class="${className}" role="img" aria-label="Imagem não cadastrada"><span aria-hidden="true">▧</span>Imagem não cadastrada</div>`;
    }

    async function carregarOrdens() {
      const grid = document.getElementById('ordensgrid');
      try {
        const resp = await fetch('api.php?action=ordens');
        if (!resp.ok) throw new Error('Falha ao carregar ordens');
        const data = await resp.json();
        grid.innerHTML = '';

        data.forEach(ordem => {
          const id = Number(ordem.id);
          const nome = escapeHtml(ordem.nome);
          const card = document.createElement('article');
          card.className = 'card';

          const imgHtml = ordem.imagem ?
            `<img src="${escapeHtml(ordem.imagem)}" class="card-img" alt="${nome}" loading="lazy">` :
            imagemAusente('card-img-placeholder');

          card.innerHTML = `
            ${imgHtml}
            <div class="card-body">
              <div class="card-nome">${nome}</div>
              <div class="card-acoes">
                <button type="button" class="btn btn-outline" data-modal-id="${id}">Ver características</button>
                <a class="btn btn-solid" href="chave.php?ordem=${id}">Iniciar identificação</a>
              </div>
            </div>`;
          grid.appendChild(card);
        });
      } catch (erro) {
        grid.innerHTML = '<p class="empty-feedback">Não foi possível carregar as ordens neste momento.</p>';
      }
    }

    async function abrirModal(ordemId, acionador) {
      modalTrigger = acionador || document.activeElement;
      const resp = await fetch(`api.php?action=ordem&id=${ordemId}`);
      const d = await resp.json();

      document.getElementById('modalTitulo').textContent = d.nome;
      document.getElementById('modalChaveBtn').href = `chave.php?ordem=${d.id}`;

      let caract = [];
      try {
        caract = d.caracteristicas ? JSON.parse(d.caracteristicas) : [];
      } catch (erro) {
        caract = [];
      }
      const caracterHtml = caract.length ? `
    <div class="modal-section-title">Características Gerais</div>
    <ul class="modal-list">${caract.map(c => `<li>${escapeHtml(c)}</li>`).join('')}</ul>
  ` : '';

      const exemplosHtml = d.exemplos ? `
    <div class="modal-section-title">Exemplos</div>
    <p class="modal-text">${escapeHtml(d.exemplos)}</p>
  ` : '';

      const agricolaHtml = d.importancia_agricola ? `
    <div class="modal-section-title">Importância Agrícola</div>
    <p class="modal-text">${escapeHtml(d.importancia_agricola)}</p>
  ` : '';

      const familiasHtml = d.familias && d.familias.length ? `
    <div class="modal-section-title">Famílias Incluídas</div>
    <div class="modal-tags">${d.familias.map(f => `<span class="tag">${escapeHtml(f)}</span>`).join('')}</div>
  ` : '';

      const imgHtml = d.imagem ?
        `<img src="${escapeHtml(d.imagem)}" class="modal-img" alt="${escapeHtml(d.nome)}">` :
        imagemAusente('modal-img-placeholder');

      document.getElementById('modalBody').innerHTML = imgHtml + caracterHtml + exemplosHtml + agricolaHtml + familiasHtml;
      modalOverlay.classList.add('open');
      modalOverlay.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
      modalContent.focus();
    }

    function fecharModal() {
      if (!modalOverlay.classList.contains('open')) return;
      modalOverlay.classList.remove('open');
      modalOverlay.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
      if (modalTrigger) modalTrigger.focus();
    }

    document.getElementById('ordensgrid').addEventListener('click', e => {
      const button = e.target.closest('[data-modal-id]');
      if (button) abrirModal(button.dataset.modalId, button);
    });
    document.getElementById('modalClose').addEventListener('click', fecharModal);
    modalOverlay.addEventListener('click', e => {
      if (e.target === modalOverlay) fecharModal();
    });
    document.addEventListener('keydown', e => {
      if (!modalOverlay.classList.contains('open')) return;
      if (e.key === 'Escape') {
        fecharModal();
        return;
      }
      if (e.key !== 'Tab') return;

      const focusable = [...modalContent.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])')];
      if (!focusable.length) {
        e.preventDefault();
        modalContent.focus();
        return;
      }
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (e.shiftKey && (document.activeElement === first || document.activeElement === modalContent)) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    });
    carregarOrdens();
  </script>
</body>

</html>
