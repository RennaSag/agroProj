<?php require_once '../includes/db.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chaves Dicotômicas - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --verde: #3a6b35;
      --verde-escuro: #2c5228;
      --verde-bg: #f0f5ef;
      --verde-borda: #c8dcc6;
      --texto: #1a2e18;
      --texto-suave: #4a6648;
      --branco: #fff;
      --sombra: 0 4px 20px rgba(42, 82, 40, 0.10)
    }

    body {
      font-family: 'Source Sans 3', sans-serif;
      background: #f5f7f5;
      color: var(--texto)
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      width: 240px;
      background: var(--verde-escuro);
      z-index: 100;
      display: flex;
      flex-direction: column
    }

    .sidebar-logo {
      padding: 24px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.12)
    }

    .sidebar-logo h2 {
      font-family: 'Playfair Display', serif;
      color: #fff;
      font-size: 1.1rem;
      font-weight: 700
    }

    .sidebar-logo p {
      color: rgba(255, 255, 255, 0.55);
      font-size: 0.8rem;
      margin-top: 3px
    }

    .nav {
      flex: 1;
      padding: 16px 0
    }

    .nav a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 20px;
      color: rgba(255, 255, 255, 0.80);
      text-decoration: none;
      font-size: 0.92rem;
      transition: all 0.15s
    }

    .nav a:hover,
    .nav a.active {
      background: rgba(255, 255, 255, 0.12);
      color: #fff
    }

    .nav a.active {
      border-left: 3px solid rgba(255, 255, 255, 0.70)
    }

    .nav-section {
      padding: 16px 20px 6px;
      color: rgba(255, 255, 255, 0.40);
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.1em
    }

    .sidebar-bottom {
      padding: 16px 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.10)
    }

    .sidebar-bottom a {
      color: rgba(255, 255, 255, 0.60);
      text-decoration: none;
      font-size: 0.85rem
    }

    .main {
      margin-left: 240px;
      min-height: 100vh
    }

    .topbar {
      background: var(--branco);
      border-bottom: 1px solid var(--verde-borda);
      padding: 16px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .topbar h1 {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--verde-escuro)
    }

    .content {
      padding: 32px
    }

    .card {
      background: var(--branco);
      border-radius: 14px;
      border: 1px solid var(--verde-borda);
      box-shadow: var(--sombra);
      overflow: hidden;
      margin-bottom: 28px
    }

    .card-header {
      padding: 20px 28px;
      border-bottom: 1px solid var(--verde-borda);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px
    }

    .card-header h3 {
      font-size: 1rem;
      color: var(--verde-escuro);
      font-weight: 600
    }

    .card-body {
      padding: 28px
    }

    .form-group {
      margin-bottom: 20px
    }

    label.lbl {
      display: block;
      font-weight: 600;
      font-size: 0.85rem;
      color: var(--texto);
      margin-bottom: 7px;
      text-transform: uppercase;
      letter-spacing: 0.04em
    }

    .form-control {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid var(--verde-borda);
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.95rem;
      color: var(--texto);
      transition: border 0.18s
    }

    .form-control:focus {
      outline: none;
      border-color: var(--verde)
    }

    textarea.form-control {
      min-height: 80px;
      resize: vertical
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px
    }

    .form-row-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px
    }

    .btn-primary {
      background: var(--verde);
      color: #fff;
      padding: 11px 22px;
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.15s
    }

    .btn-primary:hover {
      background: var(--verde-escuro)
    }

    .btn-secondary {
      background: var(--verde-bg);
      color: var(--verde);
      border: 1.5px solid var(--verde-borda);
      padding: 10px 20px;
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px
    }

    .form-actions {
      display: flex;
      gap: 12px;
      margin-top: 8px
    }

    .alert-success {
      background: #d4edda;
      border: 1px solid #b8dabc;
      border-radius: 9px;
      padding: 12px 16px;
      color: #1a6b2e;
      margin-bottom: 20px;
      font-size: 0.92rem
    }

    .alert-error {
      background: #fef0f0;
      border: 1px solid #f5c6c6;
      border-radius: 9px;
      padding: 12px 16px;
      color: #c0392b;
      margin-bottom: 20px;
      font-size: 0.92rem
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      padding: 12px 20px;
      text-align: left;
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--texto-suave);
      background: var(--verde-bg);
      border-bottom: 1px solid var(--verde-borda)
    }

    td {
      padding: 12px 20px;
      border-bottom: 1px solid #f0f0ee;
      font-size: 0.9rem;
      vertical-align: top
    }

    tr:last-child td {
      border-bottom: none
    }

    .btn-sm {
      padding: 6px 14px;
      border-radius: 7px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.82rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.15s;
      margin-right: 6px
    }

    .btn-edit {
      background: var(--verde-bg);
      color: var(--verde);
      border: 1px solid var(--verde-borda)
    }

    .btn-edit:hover {
      background: var(--verde-borda)
    }

    .btn-del {
      background: #fef0f0;
      color: #c0392b;
      border: 1px solid #f5c6c6
    }

    .btn-del:hover {
      background: #f5c6c6
    }

    .passo-num {
      display: inline-flex;
      width: 28px;
      height: 28px;
      background: var(--verde);
      color: #fff;
      border-radius: 50%;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 600;
      margin-right: 6px
    }

    .ordem-select {
      padding: 9px 14px;
      border: 1.5px solid var(--verde-borda);
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      color: var(--texto);
      background: var(--branco);
      min-width: 260px
    }

    .hint {
      font-size: 0.82rem;
      color: var(--texto-suave);
      margin-top: 4px
    }

    .divider {
      height: 1px;
      background: var(--verde-borda);
      margin: 20px 0
    }

    .section-label {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--verde);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      margin-bottom: 12px
    }

    .resultado-box {
      background: var(--verde-bg);
      border: 1px solid var(--verde-borda);
      border-radius: 10px;
      padding: 16px
    }
  </style>
</head>

<body>
  <?php
  
  requireAdmin();
  $pdo = getDB();

  $acao = $_GET['acao'] ?? 'listar';
  $ordem_id = (int)($_GET['ordem_id'] ?? 0);
  $passo_id = (int)($_GET['passo_id'] ?? 0);
  $msg = '';
  $erro = '';

  $ordens = $pdo->query("SELECT id, nome FROM ordens WHERE ativo=1 ORDER BY ordem_exibicao, id")->fetchAll();

  function getFamilias($pdo, $ordem_id)
  {
    if (!$ordem_id) return [];
    $stmt = $pdo->prepare("SELECT id, nome FROM familias WHERE ordem_id=? AND ativo=1 ORDER BY nome");
    $stmt->execute([$ordem_id]);
    return $stmt->fetchAll();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oid = (int)$_POST['ordem_id'];
    $pergunta = trim($_POST['pergunta'] ?? '');
    $passo_num = (int)$_POST['passo_numero'];
    $sim_texto = trim($_POST['opcao_sim_texto'] ?? '');
    $nao_texto = trim($_POST['opcao_nao_texto'] ?? '');
    $sim_passo = $_POST['sim_leva_passo'] !== '' ? (int)$_POST['sim_leva_passo'] : null;
    $nao_passo = $_POST['nao_leva_passo'] !== '' ? (int)$_POST['nao_leva_passo'] : null;
    $sim_fam = $_POST['sim_resultado_familia_id'] !== '' ? (int)$_POST['sim_resultado_familia_id'] : null;
    $nao_fam = $_POST['nao_resultado_familia_id'] !== '' ? (int)$_POST['nao_resultado_familia_id'] : null;

    if ($_POST['form_acao'] === 'novo_passo') {
      $stmt = $pdo->prepare("INSERT INTO chave_passos (ordem_id,passo_numero,pergunta,opcao_sim_texto,opcao_nao_texto,sim_leva_passo,nao_leva_passo,sim_resultado_familia_id,nao_resultado_familia_id) VALUES (?,?,?,?,?,?,?,?,?)");
      $stmt->execute([$oid, $passo_num, $pergunta, $sim_texto, $nao_texto, $sim_passo, $nao_passo, $sim_fam, $nao_fam]);
      $msg = 'Passo adicionado!';
    } elseif ($_POST['form_acao'] === 'editar_passo') {
      $pid = (int)$_POST['passo_id'];
      $stmt = $pdo->prepare("UPDATE chave_passos SET passo_numero=?,pergunta=?,opcao_sim_texto=?,opcao_nao_texto=?,sim_leva_passo=?,nao_leva_passo=?,sim_resultado_familia_id=?,nao_resultado_familia_id=? WHERE id=?");
      $stmt->execute([$passo_num, $pergunta, $sim_texto, $nao_texto, $sim_passo, $nao_passo, $sim_fam, $nao_fam, $pid]);
      $msg = 'Passo atualizado!';
    }
    $acao = 'listar';
  }

  if ($acao === 'del_passo' && $passo_id) {
    $pdo->prepare("DELETE FROM chave_passos WHERE id=?")->execute([$passo_id]);
    $msg = 'Passo excluído.';
    $acao = 'listar';
  }

  $passo_edit = null;
  if ($acao === 'editar' && $passo_id) {
    $stmt = $pdo->prepare("SELECT * FROM chave_passos WHERE id=?");
    $stmt->execute([$passo_id]);
    $passo_edit = $stmt->fetch();
    if ($passo_edit) $ordem_id = $passo_edit['ordem_id'];
  }

  $passos = [];
  if ($ordem_id) {
    $stmt = $pdo->prepare("
        SELECT cp.*, 
               fs.nome AS sim_fam_nome, fn.nome AS nao_fam_nome
        FROM chave_passos cp
        LEFT JOIN familias fs ON fs.id = cp.sim_resultado_familia_id
        LEFT JOIN familias fn ON fn.id = cp.nao_resultado_familia_id
        WHERE cp.ordem_id = ?
        ORDER BY cp.passo_numero
    ");
    $stmt->execute([$ordem_id]);
    $passos = $stmt->fetchAll();
  }

  $familias = getFamilias($pdo, $ordem_id);
  $prox_passo = 1;
  if ($passos) $prox_passo = max(array_column($passos, 'passo_numero')) + 1;
  ?>

  <nav class="sidebar">
    <div class="sidebar-logo">
      <h2>Entomologia</h2>
      <p>Admin</p>
    </div>
    <div class="nav">
      <div class="nav-section">Principal</div>
      <a href="index.php">Dashboard</a>
      <a href="ordens.php">Ordens</a>
      <a href="familias.php">Famílias</a>
      <a href="chaves.php" class="active">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Chaves Dicotômicas</h1>
      <?php if ($ordem_id): ?>
        <a href="../chave.php?ordem=<?= $ordem_id ?>" target="_blank" class="btn-secondary">Ver Chave no Site</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      
      <div class="card">
        <div class="card-header">
          <h3>Selecionar Ordem para Editar Chave</h3>
        </div>
        <div class="card-body" style="padding:20px 28px">
          <select class="ordem-select" onchange="location.href='chaves.php?ordem_id='+this.value">
            <option value="0">Selecione uma ordem.</option>
            <?php foreach ($ordens as $o): ?>
              <option value="<?= $o['id'] ?>" <?= $ordem_id == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if ($ordem_id):
        $ordem_atual = $pdo->prepare("SELECT nome FROM ordens WHERE id=?");
        $ordem_atual->execute([$ordem_id]);
        $oNome = $ordem_atual->fetchColumn();
      ?>

      
        <div class="card">
          <div class="card-header">
            <h3>Passos da Chave: <em><?= htmlspecialchars($oNome) ?></em></h3>
            <span style="font-size:0.85rem;color:var(--texto-suave)"><?= count($passos) ?> passos cadastrados</span>
          </div>
          <?php if ($passos): ?>
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Pergunta</th>
                  <th>SIM</th>
                  <th>NÃO</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($passos as $p): ?>
                  <tr>
                    <td><span class="passo-num"><?= $p['passo_numero'] ?></span></td>
                    <td style="max-width:300px"><?= htmlspecialchars(mb_strimwidth($p['pergunta'], 0, 80, '…')) ?></td>
                    <td style="font-size:0.85rem">
                      <?php if ($p['sim_resultado_familia_id']): ?>
                        <strong style="color:var(--verde)"><em><?= htmlspecialchars($p['sim_fam_nome']) ?></em></strong>
                      <?php elseif ($p['sim_leva_passo']): ?>
                        Passo <?= $p['sim_leva_passo'] ?>
                      <?php else: echo '—';
                      endif; ?>
                    </td>
                    <td style="font-size:0.85rem">
                      <?php if ($p['nao_resultado_familia_id']): ?>
                        <strong style="color:#c0392b"><em><?= htmlspecialchars($p['nao_fam_nome']) ?></em></strong>
                      <?php elseif ($p['nao_leva_passo']): ?>
                      Passo <?= $p['nao_leva_passo'] ?>
                      <?php else: echo '—';
                      endif; ?>
                    </td>
                    <td>
                      <a href="?acao=editar&passo_id=<?= $p['id'] ?>&ordem_id=<?= $ordem_id ?>" class="btn-sm btn-edit">Editar</a>
                      <a href="?acao=del_passo&passo_id=<?= $p['id'] ?>&ordem_id=<?= $ordem_id ?>" class="btn-sm btn-del" onclick="return confirm('Excluir este passo?')">Excluir</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div style="padding:32px;text-align:center;color:var(--texto-suave)">Nenhum passo cadastrado para esta ordem.</div>
          <?php endif; ?>
        </div>

       
        <div class="card">
          <div class="card-header">
            <h3><?= ($acao === 'editar' && $passo_edit) ? 'Editar Passo ' . $passo_edit['passo_numero'] : 'Adicionar Novo Passo' ?></h3>
          </div>
          <div class="card-body">
            <form method="POST">
              <input type="hidden" name="form_acao" value="<?= ($acao === 'editar' && $passo_edit) ? 'editar_passo' : 'novo_passo' ?>">
              <input type="hidden" name="ordem_id" value="<?= $ordem_id ?>">
              <?php if ($acao === 'editar' && $passo_edit): ?>
                <input type="hidden" name="passo_id" value="<?= $passo_edit['id'] ?>">
              <?php endif; ?>
              <?php $pe = $passo_edit ?: []; ?>

              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">Número do Passo</label>
                  <input type="number" name="passo_numero" class="form-control" required min="1" value="<?= $pe['passo_numero'] ?? $prox_passo ?>">
                  <p class="hint">Passos são exibidos em ordem crescente.</p>
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Pergunta Dicotômica</label>
                <textarea name="pergunta" class="form-control" required placeholder="Ex: Inseto de tamanho grande (>2cm) com órgão estridulador nos machos?"><?= htmlspecialchars($pe['pergunta'] ?? '') ?></textarea>
              </div>

              <div class="divider"></div>
              <div class="section-label">Resposta SIM</div>
              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">Texto da opção SIM</label>
                  <input type="text" name="opcao_sim_texto" class="form-control" value="<?= htmlspecialchars($pe['opcao_sim_texto'] ?? '') ?>" placeholder="Descrição breve da característica SIM">
                </div>
              </div>
              <div class="resultado-box" style="margin-bottom:20px">
                <div class="form-row-3">
                  <div class="form-group" style="margin-bottom:0">
                    <label class="lbl">Sim: Próximo passo</label>
                    <input type="number" name="sim_leva_passo" class="form-control" min="1" value="<?= $pe['sim_leva_passo'] ?? '' ?>" placeholder="N° do passo">
                    <p class="hint">Deixe vazio se leva a uma família.</p>
                  </div>
                  <div class="form-group" style="margin-bottom:0;grid-column:span 2">
                    <label class="lbl">SIM: Resultado (família)</label>
                    <select name="sim_resultado_familia_id" class="form-control">
                      <option value="">Não é resultado final</option>
                      <?php foreach ($familias as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= ($pe['sim_resultado_familia_id'] ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="divider"></div>
              <div class="section-label" style="color:#c0392b">Resposta NÃO</div>
              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">Texto da opção NÃO</label>
                  <input type="text" name="opcao_nao_texto" class="form-control" value="<?= htmlspecialchars($pe['opcao_nao_texto'] ?? '') ?>" placeholder="Descrição breve da característica NÃO">
                </div>
              </div>
              <div class="resultado-box" style="margin-bottom:20px">
                <div class="form-row-3">
                  <div class="form-group" style="margin-bottom:0">
                    <label class="lbl">NÃO: Próximo passo</label>
                    <input type="number" name="nao_leva_passo" class="form-control" min="1" value="<?= $pe['nao_leva_passo'] ?? '' ?>" placeholder="N° do passo">
                  </div>
                  <div class="form-group" style="margin-bottom:0;grid-column:span 2">
                    <label class="lbl">NÃO: Resultado (família)</label>
                    <select name="nao_resultado_familia_id" class="form-control">
                      <option value="">Não é resultado final</option>
                      <?php foreach ($familias as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= ($pe['nao_resultado_familia_id'] ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Passo</button>
                <?php if ($acao === 'editar'): ?>
                  <a href="chaves.php?ordem_id=<?= $ordem_id ?>" class="btn-secondary">Cancelar</a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

      <?php endif; ?>
    </div>
  </div>
</body>

</html>