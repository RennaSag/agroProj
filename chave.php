<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chave Dicotômica</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="assets/css/site-chave.css?v=20260601-2">
</head>

<body>

  <header>
    <a class="header-back" href="index.php" aria-label="Voltar">&#8592;</a>
    <div class="header-info">
      <h1 id="headerTitulo">Carregando.</h1>
      <p>Chave dicotômica</p>
    </div>
  </header>

  <div class="chave-main">
    <div class="progress-status">
      <span class="progress-label" id="progressLabel">Progresso da identificação</span>
      <span class="progress-text" id="progressText">Carregando.</span>
    </div>
    <div class="progress-bar" id="progressBar" role="progressbar" aria-labelledby="progressLabel" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-valuetext="Carregando.">
      <div class="progress-fill" id="progressFill" style="width:0%"></div>
    </div>
    <div class="sr-only" id="statusLive" aria-live="polite" aria-atomic="true"></div>
    <div id="conteudo">
      <div style="text-align:center;padding:40px;color:var(--texto-suave)">Carregando.</div>
    </div>
    <aside class="history-panel" id="historyPanel" aria-labelledby="historyTitle" hidden>
      <div class="history-header">
        <h2 id="historyTitle">Histórico da identificação</h2>
        <button type="button" class="back-step" id="backStep" disabled>Voltar ao passo anterior</button>
      </div>
      <ol class="history-list" id="historyList"></ol>
    </aside>
  </div>

  <script>
    const params = new URLSearchParams(location.search);
    const ordemId = params.get('ordem');
    let passos = [];
    let currentIndex = 0;
    let ordemNome = '';
    let choiceHistory = [];
    let transitioning = false;
    let exibirMiniaturasHistorico = true;
    let examplePopoverTrigger = null;

    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const statusLive = document.getElementById('statusLive');
    const conteudo = document.getElementById('conteudo');
    const historyPanel = document.getElementById('historyPanel');
    const historyList = document.getElementById('historyList');
    const backStep = document.getElementById('backStep');

    function escapeHtml(value) {
      return String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      }[char]));
    }

    function announce(message) {
      statusLive.textContent = '';
      window.requestAnimationFrame(() => {
        statusLive.textContent = message;
      });
    }

    function updateProgress(passoAtual, total, concluido = false) {
      const progresso = concluido ? 100 : Math.round((passoAtual / total) * 100);
      const texto = concluido ?
        'Identificação concluída' :
        `Passo ${passoAtual}`;

      progressFill.style.width = `${progresso}%`;
      progressBar.setAttribute('aria-valuenow', String(progresso));
      progressBar.setAttribute('aria-valuetext', texto);
      progressText.textContent = texto;
    }

    function renderHistory() {
      historyPanel.hidden = false;
      backStep.disabled = choiceHistory.length === 0;

      if (!choiceHistory.length) {
        historyList.innerHTML = '<li class="history-empty">Nenhuma escolha registrada nesta identificação.</li>';
        return;
      }

      historyList.innerHTML = choiceHistory.map(item => `
        <li>
          <div class="history-content">
            <span class="history-step">Passo ${item.stepNumber}</span>
            <strong>${escapeHtml(item.label)}</strong>
            <span>${escapeHtml(item.description)}</span>
          </div>
          ${renderHistoryThumb(item)}
        </li>`).join('');
    }

    function renderHistoryThumb(item) {
      if (!exibirMiniaturasHistorico) return '';

      if (item.image) {
        return `<img src="${escapeHtml(item.image)}" class="history-thumb" alt="Imagem da ${escapeHtml(item.label.toLowerCase())} selecionada no passo ${item.stepNumber}">`;
      }

      return `
        <div class="history-thumb history-thumb-empty" role="img" aria-label="Imagem não cadastrada para a ${escapeHtml(item.label.toLowerCase())} selecionada no passo ${item.stepNumber}">
          <span aria-hidden="true">▧</span>
          <small>Sem imagem</small>
        </div>`;
    }

    function focusCurrentHeading(id) {
      const heading = document.getElementById(id);
      if (heading) heading.focus();
    }

    async function init() {
      if (!ordemId) {
        location.href = 'index.php';
        return;
      }

      try {
        const ro = await fetch(`api.php?action=ordem&id=${ordemId}`);
        const ordem = await ro.json();
        ordemNome = ordem.nome;
        document.getElementById('headerTitulo').textContent = ordemNome;
        document.title = ordemNome;

        try {
          const rc = await fetch('api.php?action=configuracoes_chave');
          if (rc.ok) {
            const configuracoes = await rc.json();
            exibirMiniaturasHistorico = configuracoes.exibir_miniaturas_historico !== false;
          }
        } catch (erroConfig) {
          exibirMiniaturasHistorico = true;
        }

        const rp = await fetch(`api.php?action=passos&ordem_id=${ordemId}`);
        passos = await rp.json();
        choiceHistory = [];

        if (!passos || passos.length === 0) {
          progressText.textContent = 'Chave não cadastrada';
          progressBar.setAttribute('aria-valuetext', 'Chave não cadastrada');
          conteudo.innerHTML = `
            <div class="sem-chave">
              <h3>Chave dicotômica não cadastrada</h3>
              <p>A chave para <em>${escapeHtml(ordemNome)}</em> ainda não foi inserida pelo administrador.<br>Entre em contato com o responsável pela disciplina.</p>
              <a href="index.php" class="btn btn-solid sem-chave-back">Voltar ao início</a>
            </div>`;
          announce(`Chave dicotômica não cadastrada para ${ordemNome}.`);
          return;
        }

        renderHistory();
        renderPasso(0, false);
      } catch (erro) {
        conteudo.innerHTML = '<div class="sem-chave"><h3>Não foi possível carregar a chave</h3><p>Tente novamente em instantes.</p></div>';
        announce('Não foi possível carregar a chave dicotômica.');
      }
    }

    function renderPasso(idx, moveFocus = true) {
      currentIndex = idx;
      const p = passos[idx];
      const total = p.total_passos;
      const passoAtual = idx + 1;

      transitioning = false;
      updateProgress(passoAtual, total);
      conteudo.innerHTML = `
        <section class="specimen-step" aria-labelledby="perguntaAtual">
          <h2 class="passo-pergunta" id="perguntaAtual" tabindex="-1">${escapeHtml(p.pergunta)}</h2>
          <div class="specimen-compare">
            ${renderOpcao('sim', idx, 'OPÇÃO A', p.opcao_sim_texto, p.sim_imagem)}
            <div class="compare-divider" aria-hidden="true">OU</div>
            ${renderOpcao('nao', idx, 'OPÇÃO B', p.opcao_nao_texto, p.nao_imagem)}
          </div>
        </section>`;
      if (moveFocus) focusCurrentHeading('perguntaAtual');
      announce(`Passo ${passoAtual} de ${total}. ${p.pergunta}`);
    }

    function renderOpcao(escolha, idx, label, texto, imagem) {
      const titulo = texto || (escolha === 'sim' ? 'Característica presente' : 'Característica ausente');
      const nomeOpcao = escolha === 'sim' ? 'opção A' : 'opção B';
      const imagemHtml = imagem ?
        `<img src="${escapeHtml(imagem)}" class="specimen-img" alt="${escapeHtml(titulo)}">` :
        `<div class="specimen-img-placeholder" role="img" aria-label="Imagem não cadastrada"><span aria-hidden="true">▧</span><small>Sem imagem</small></div>`;

      return `
        <article class="specimen-option">
          <div class="option-kicker">${label}</div>
          <div class="specimen-media">${imagemHtml}</div>
          <div class="specimen-copy">
            <h3>${escapeHtml(titulo)}</h3>
            ${texto ? `<p>${escapeHtml(texto)}</p>` : '<p>Compare o espécime com esta alternativa.</p>'}
          </div>
          <button type="button" class="select-option" data-choice="${escolha}" data-step-index="${idx}" aria-label="Selecionar ${nomeOpcao}: ${escapeHtml(titulo)}">Selecionar ${nomeOpcao}</button>
        </article>`;
    }

    function responder(escolha, idx, btn) {
      if (transitioning || idx !== currentIndex) return;
      transitioning = true;
      document.querySelectorAll('.select-option').forEach(option => {
        option.disabled = true;
      });
      btn.closest('.specimen-option').classList.add('selected');
      const p = passos[idx];
      const isSim = escolha === 'sim';
      const label = isSim ? 'Opção A' : 'Opção B';
      const description = isSim ? p.opcao_sim_texto : p.opcao_nao_texto;
      const image = isSim ? p.sim_imagem : p.nao_imagem;

      choiceHistory.push({
        fromIndex: idx,
        stepNumber: idx + 1,
        label,
        description: description || (isSim ? 'Característica presente' : 'Característica ausente'),
        image: image || ''
      });
      renderHistory();

      setTimeout(() => {
        if (isSim) {
          if (p.sim_resultado_familia_id) {
            renderResultado({
              nome: p.sim_familia,
              descricao: p.sim_desc,
              exemplos: p.sim_ex,
              imagem: p.sim_img,
              exemplo_imagens: p.sim_exemplo_imagens || []
            });
            return;
          } else if (p.sim_leva_passo) {
            const nextIdx = passos.findIndex(x => x.passo_numero == p.sim_leva_passo);
            if (nextIdx >= 0) {
              renderPasso(nextIdx);
              return;
            }
          }
        } else {
          if (p.nao_resultado_familia_id) {
            renderResultado({
              nome: p.nao_familia,
              descricao: p.nao_desc,
              exemplos: p.nao_ex,
              imagem: p.nao_img,
              exemplo_imagens: p.nao_exemplo_imagens || []
            });
            return;
          } else if (p.nao_leva_passo) {
            const nextIdx = passos.findIndex(x => x.passo_numero == p.nao_leva_passo);
            if (nextIdx >= 0) {
              renderPasso(nextIdx);
              return;
            }
          }
        }

        transitioning = false;
        document.querySelectorAll('.select-option').forEach(option => {
          option.disabled = false;
        });
        choiceHistory.pop();
        renderHistory();
        announce('Não foi possível avançar para o próximo passo. Tente novamente.');
      }, 160);
    }

    function renderExampleImages(familia) {
      const imagens = Array.isArray(familia.exemplo_imagens) ? familia.exemplo_imagens : [];
      if (!imagens.length) return '';

      const nomeFamilia = familia.nome || 'família identificada';
      const thumbs = imagens.map((item, index) => {
        const src = item.imagem || item;
        const label = `Imagem de exemplo ${index + 1} de ${nomeFamilia}`;
        return `
          <button type="button" class="example-thumb" data-example-src="${escapeHtml(src)}" data-example-alt="${escapeHtml(label)}" aria-label="Ampliar ${escapeHtml(label)}">
            <img src="${escapeHtml(src)}" alt="${escapeHtml(label)}">
          </button>`;
      }).join('');

      return `
        <div class="resultado-example-gallery" aria-label="Imagens de exemplos da família">
          <div class="example-thumbs">${thumbs}</div>
          <div class="example-popover" id="examplePopover" role="dialog" aria-modal="true" aria-label="Imagem ampliada do exemplo" hidden>
            <div class="example-popover-card">
              <button type="button" class="example-popover-close" id="closeExamplePopover" aria-label="Fechar imagem ampliada">&times;</button>
              <img id="examplePopoverImg" src="" alt="">
            </div>
          </div>
        </div>`;
    }

    function openExamplePopover(button) {
      const popover = document.getElementById('examplePopover');
      const popoverImg = document.getElementById('examplePopoverImg');
      const closeButton = document.getElementById('closeExamplePopover');
      const gallery = button.closest('.resultado-example-gallery');
      if (!popover || !popoverImg || !closeButton || !gallery) return;

      examplePopoverTrigger = button;
      popoverImg.src = button.dataset.exampleSrc;
      popoverImg.alt = button.dataset.exampleAlt || 'Imagem de exemplo ampliada';
      popover.hidden = false;
      document.body.classList.add('example-viewer-open');
      closeButton.focus();
    }

    function closeExamplePopover(returnFocus = true) {
      const popover = document.getElementById('examplePopover');
      const popoverImg = document.getElementById('examplePopoverImg');
      if (!popover || popover.hidden) return;

      popover.hidden = true;
      if (popoverImg) {
        popoverImg.removeAttribute('src');
        popoverImg.alt = '';
      }
      document.body.classList.remove('example-viewer-open');

      if (returnFocus && examplePopoverTrigger) {
        examplePopoverTrigger.focus();
      }
      examplePopoverTrigger = null;
    }

    function renderResultado(familia) {
      transitioning = false;
      updateProgress(1, 1, true);

      const imgHtml = familia.imagem ?
        `<img src="${escapeHtml(familia.imagem)}" class="resultado-img" alt="${escapeHtml(familia.nome)}">` :
        `<div class="resultado-img-placeholder" role="img" aria-label="Imagem não cadastrada"><span aria-hidden="true">▧</span>Imagem não cadastrada</div>`;
      const exampleImagesHtml = renderExampleImages(familia);

      conteudo.innerHTML = `
        <div class="resultado-card" aria-labelledby="resultadoTitulo">
          <div class="resultado-topo">
            <div class="resultado-check" aria-hidden="true">&#10003;</div>
            <div class="resultado-label">Família identificada</div>
            <h2 class="resultado-familia" id="resultadoTitulo" tabindex="-1">${escapeHtml(familia.nome || '-')}</h2>
            <div class="resultado-ordem">Ordem: <em>${escapeHtml(ordemNome)}</em></div>
          </div>
          ${imgHtml}
          ${familia.descricao || familia.exemplos || exampleImagesHtml ? `
            <div class="resultado-desc">
              ${familia.descricao ? `<p>${escapeHtml(familia.descricao)}</p>` : ''}
              ${familia.exemplos ? `<p class="resultado-exemplos"><strong>Exemplos:</strong> ${escapeHtml(familia.exemplos)}</p>` : ''}
              ${exampleImagesHtml}
            </div>` : ''}
          <div class="resultado-acoes">
            <button type="button" class="btn btn-outline" id="restartKey">Refazer identificação</button>
            <a class="btn btn-solid" href="index.php">Voltar ao início</a>
          </div>
        </div>`;
      focusCurrentHeading('resultadoTitulo');
      announce(`Família identificada: ${familia.nome || 'não informada'}.`);
    }

    function voltarPassoAnterior() {
      if (transitioning || !choiceHistory.length) return;

      const anterior = choiceHistory.pop();
      renderHistory();
      renderPasso(anterior.fromIndex);
    }

    function reiniciarIdentificacao() {
      choiceHistory = [];
      transitioning = false;
      renderHistory();
      renderPasso(0);
    }

    conteudo.addEventListener('click', event => {
      const option = event.target.closest('.select-option');
      if (option) {
        responder(option.dataset.choice, Number(option.dataset.stepIndex), option);
        return;
      }

      const exampleThumb = event.target.closest('.example-thumb');
      if (exampleThumb) {
        openExamplePopover(exampleThumb);
        return;
      }

      if (event.target.closest('#closeExamplePopover')) {
        closeExamplePopover();
        return;
      }

      if (event.target.closest('#restartKey')) {
        reiniciarIdentificacao();
      }
    });
    backStep.addEventListener('click', voltarPassoAnterior);
    document.addEventListener('click', event => {
      const popover = document.getElementById('examplePopover');
      if (!popover || popover.hidden) return;
      if (event.target.closest('.example-thumb')) return;
      if (event.target === popover) {
        closeExamplePopover(false);
      }
    });
    document.addEventListener('focusin', event => {
      const popover = document.getElementById('examplePopover');
      if (!popover || popover.hidden) return;
      if (event.target.closest('#examplePopover') || event.target.closest('.example-thumb')) return;
      closeExamplePopover(false);
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape') {
        closeExamplePopover();
      }
    });

    init();
  </script>
</body>

</html>
