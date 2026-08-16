<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Entomológica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="assets/css/site-home.css?v=20260815">
</head>

<body>

  <header class="site-header">
    <div class="header-top">
      <div class="brand">
        <p class="brand-kicker">IF Goiano &nbsp;·&nbsp; Campus Iporá</p>
        <h1>Chaves de Classificação Entomológica</h1>
      </div>
    </div>

    <div class="header-search">
      <div class="search-box">
        <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
        </svg>
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar por ordem ou família..."
          autocomplete="off" spellcheck="false" role="combobox" aria-expanded="false"
          aria-controls="searchResults" aria-autocomplete="list" aria-label="Buscar ordem ou família">
        <button type="button" class="search-clear" id="searchClear" hidden aria-label="Limpar busca">&times;</button>
      </div>
      <div class="search-results" id="searchResults" role="listbox" hidden></div>
    </div>
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


<!--Sessão com os creditos-->
<footer class="site-footer">
    <div class="footer-content">
      <p class="footer-creditos">
        Desenvolvido por <strong>Rennã Samuel Andrade Gonçalves, Saymon Henrique Costa Cassiano, Uender Barbosa de Souza, Rian Tavares Vieira, Joao Victor Almeida Amorim Gomes, Jhonata Ribeiro Sampaio</strong>
      </p>
      <p class="footer-orientadora">
        Orientadora: <strong>Lais Candido Rodrigues da Silva Lopes</strong>
      </p>

      <p class="footer-creditos">
        Equipe de agronomia: <strong>Ana Gabriela de Castro Silva, Gabrielly Pereira de Araújo, Marcus Vinícius Silveira Silva, João Paulo Rodrigues Marques</strong>
      </p>
      <p class="footer-orientadora">
        Orientadora: <strong>Daline Benites Bottega</strong>
      </p>

      <!--
      <p class="footer-copyright">
        &copy; 2026 Classificação de Chaves Entomológicas. Todos os direitos reservados.
      </p>
      -->

      <a href="admin/check_auth.php" class="footer-admin-link">Área administrativa</a>
    </div>
  </footer>

  <div class="modal-overlay" id="modalOverlay" aria-hidden="true">
    <div class="modal" id="modalContent" role="dialog" aria-modal="true" aria-labelledby="modalTitulo" tabindex="-1">
      <div class="modal-header">
        <div>
          <p class="modal-kicker" id="modalKicker">Ordem</p>
          <h3 id="modalTitulo">-</h3>
        </div>
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

          const totalFamilias = Number(ordem.total_familias || 0);
          const familiasLabel = totalFamilias === 1 ? '1 família' : `${totalFamilias} famílias`;

          card.innerHTML = `
            <div class="card-media">
              ${imgHtml}
              <span class="card-badge">${familiasLabel}</span>
            </div>
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

      document.getElementById('modalKicker').textContent = 'Ordem';
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

    async function abrirModalFamilia(familiaId, acionador) {
      modalTrigger = acionador || document.activeElement;
      const resp = await fetch(`api.php?action=familia&id=${familiaId}`);
      const d = await resp.json();
      if (!d || !d.id) return;

      document.getElementById('modalKicker').textContent = `Família · ${escapeHtml(d.ordem_nome || '')}`;
      document.getElementById('modalTitulo').textContent = d.nome;
      document.getElementById('modalChaveBtn').href = `chave.php?ordem=${d.ordem_id}`;

      const descricaoHtml = d.descricao ? `<p class="modal-text">${escapeHtml(d.descricao)}</p>` : '';
      const exemplosHtml = d.exemplos ? `
    <div class="modal-section-title">Exemplos</div>
    <p class="modal-text">${escapeHtml(d.exemplos)}</p>
  ` : '';

      const galeria = Array.isArray(d.exemplo_imagens) ? d.exemplo_imagens : [];
      const galeriaHtml = galeria.length ? `
    <div class="modal-section-title">Imagens de exemplo</div>
    <div class="modal-gallery">${galeria.map(img => `<img src="${escapeHtml(img.imagem)}" alt="Exemplo de ${escapeHtml(d.nome)}" loading="lazy">`).join('')}</div>
  ` : '';

      const imgHtml = d.imagem ?
        `<img src="${escapeHtml(d.imagem)}" class="modal-img" alt="${escapeHtml(d.nome)}">` :
        imagemAusente('modal-img-placeholder');

      document.getElementById('modalBody').innerHTML = imgHtml + descricaoHtml + exemplosHtml + galeriaHtml;
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
    // Busca
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    const searchResults = document.getElementById('searchResults');
    let searchDebounce = null;
    let searchItems = [];
    let searchActiveIndex = -1;
    let searchAbortController = null;

    function fecharBusca() {
      searchResults.hidden = true;
      searchResults.innerHTML = '';
      searchInput.setAttribute('aria-expanded', 'false');
      searchInput.removeAttribute('aria-activedescendant');
      searchItems = [];
      searchActiveIndex = -1;
    }

    function renderResultadosBusca(ordens, familias) {
      searchItems = [
        ...ordens.map(o => ({ tipo: 'ordem', id: o.id })),
        ...familias.map(f => ({ tipo: 'familia', id: f.id })),
      ];
      searchActiveIndex = -1;

      if (!searchItems.length) {
        searchResults.innerHTML = '<p class="search-empty">Nenhum resultado encontrado.</p>';
        searchResults.hidden = false;
        searchInput.setAttribute('aria-expanded', 'true');
        return;
      }

      const grupos = [];
      if (ordens.length) {
        grupos.push(`<p class="search-group-label">Ordens</p>` + ordens.map((o, idx) =>
          `<button type="button" class="search-item" role="option" id="search-opt-${idx}" data-tipo="ordem" data-id="${o.id}">
            <span class="search-item-nome">${escapeHtml(o.nome)}</span>
            <span class="search-item-tag">Ordem</span>
          </button>`
        ).join(''));
      }
      if (familias.length) {
        grupos.push(`<p class="search-group-label">Famílias</p>` + familias.map((f, idx) =>
          `<button type="button" class="search-item" role="option" id="search-opt-${ordens.length + idx}" data-tipo="familia" data-id="${f.id}">
            <span class="search-item-nome">${escapeHtml(f.nome)}</span>
            <span class="search-item-tag">${escapeHtml(f.ordem_nome)}</span>
          </button>`
        ).join(''));
      }

      searchResults.innerHTML = grupos.join('');
      searchResults.hidden = false;
      searchInput.setAttribute('aria-expanded', 'true');
    }

    async function executarBusca(termo) {
      if (searchAbortController) searchAbortController.abort();
      searchAbortController = new AbortController();
      try {
        const resp = await fetch(`api.php?action=buscar&q=${encodeURIComponent(termo)}`, { signal: searchAbortController.signal });
        if (!resp.ok) throw new Error('Falha na busca');
        const data = await resp.json();
        renderResultadosBusca(data.ordens || [], data.familias || []);
      } catch (erro) {
        if (erro.name !== 'AbortError') {
          searchResults.innerHTML = '<p class="search-empty">Não foi possível buscar agora.</p>';
          searchResults.hidden = false;
        }
      }
    }

    function selecionarResultadoBusca(item, acionador) {
      fecharBusca();
      if (item.tipo === 'ordem') {
        abrirModal(item.id, acionador);
      } else {
        abrirModalFamilia(item.id, acionador);
      }
    }

    function destacarItemBusca(indice) {
      const botoes = searchResults.querySelectorAll('.search-item');
      botoes.forEach(b => b.classList.remove('is-active'));
      searchActiveIndex = indice;
      if (indice >= 0 && botoes[indice]) {
        botoes[indice].classList.add('is-active');
        botoes[indice].scrollIntoView({ block: 'nearest' });
        searchInput.setAttribute('aria-activedescendant', botoes[indice].id);
      } else {
        searchInput.removeAttribute('aria-activedescendant');
      }
    }

    searchInput.addEventListener('input', () => {
      const termo = searchInput.value.trim();
      searchClear.hidden = termo.length === 0;
      clearTimeout(searchDebounce);

      if (termo.length < 2) {
        fecharBusca();
        return;
      }
      searchDebounce = setTimeout(() => executarBusca(termo), 250);
    });

    searchInput.addEventListener('keydown', e => {
      if (searchResults.hidden || !searchItems.length) return;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        destacarItemBusca(Math.min(searchActiveIndex + 1, searchItems.length - 1));
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        destacarItemBusca(Math.max(searchActiveIndex - 1, 0));
      } else if (e.key === 'Enter') {
        if (searchActiveIndex >= 0) {
          e.preventDefault();
          const botao = searchResults.querySelectorAll('.search-item')[searchActiveIndex];
          selecionarResultadoBusca(searchItems[searchActiveIndex], botao);
        }
      } else if (e.key === 'Escape') {
        fecharBusca();
      }
    });

    searchResults.addEventListener('click', e => {
      const botao = e.target.closest('.search-item');
      if (!botao) return;
      const item = searchItems.find(i => i.tipo === botao.dataset.tipo && String(i.id) === botao.dataset.id);
      if (item) selecionarResultadoBusca(item, botao);
    });

    searchClear.addEventListener('click', () => {
      searchInput.value = '';
      searchClear.hidden = true;
      fecharBusca();
      searchInput.focus();
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.header-search')) fecharBusca();
    });

    carregarOrdens();
  </script>
</body>

</html>
