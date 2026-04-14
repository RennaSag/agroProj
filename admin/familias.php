<?php require_once '../includes/db.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Famílias</title>
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
      justify-content: space-between
    }

    .card-header h3 {
      font-size: 1rem;
      color: var(--verde-escuro);
      font-weight: 600
    }

    .card-body {
      padding: 28px
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px
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
      min-height: 90px;
      resize: vertical
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
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--texto-suave);
      background: var(--verde-bg);
      border-bottom: 1px solid var(--verde-borda)
    }

    td {
      padding: 14px 20px;
      border-bottom: 1px solid #f0f0ee;
      font-size: 0.92rem
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

    .thumb {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      object-fit: cover
    }

    .thumb-ph {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      background: var(--verde-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem
    }

    .filter-bar {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      align-items: center
    }

    .filter-bar select {
      padding: 9px 14px;
      border: 1.5px solid var(--verde-borda);
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      color: var(--texto);
      background: var(--branco)
    }

    .tag-ordem {
      font-size: 0.8rem;
      background: var(--verde-bg);
      color: var(--verde);
      padding: 3px 10px;
      border-radius: 20px;
      font-style: italic
    }

    .hint {
      font-size: 0.82rem;
      color: var(--texto-suave);
      margin-top: 5px
    }

    .img-preview {
      max-width: 180px;
      border-radius: 10px;
      margin-top: 10px
    }
  </style>
</head>

<body>
  <?php
  
  requireAdmin();
  $pdo = getDB();
  $acao = $_GET['acao'] ?? 'listar';
  $id = (int)($_GET['id'] ?? 0);
  $filtro_ordem = (int)($_GET['ordem_id'] ?? 0);
  $msg = '';
  $erro = '';

 
  $ordens = $pdo->query("SELECT id, nome FROM ordens WHERE ativo=1 ORDER BY ordem_exibicao, id")->fetchAll();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $ordem_id = (int)($_POST['ordem_id'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $exemplos = trim($_POST['exemplos'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    $imagem = $_POST['imagem_atual'] ?? '';
    if (!empty($_FILES['imagem']['tmp_name'])) {
      $upload = uploadImagem($_FILES['imagem'], 'familia');
      if (is_string($upload)) $imagem = $upload;
      elseif (is_array($upload)) $erro = $upload['error'];
    }

    if (!$erro) {
      if ($_POST['form_acao'] === 'novo') {
        $stmt = $pdo->prepare("INSERT INTO familias (ordem_id,nome,descricao,exemplos,imagem,ativo) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$ordem_id, $nome, $descricao, $exemplos, $imagem, $ativo]);
        $msg = 'Família cadastrada!';
        $acao = 'listar';
      } elseif ($_POST['form_acao'] === 'editar') {
        $pid = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE familias SET ordem_id=?,nome=?,descricao=?,exemplos=?,imagem=?,ativo=? WHERE id=?");
        $stmt->execute([$ordem_id, $nome, $descricao, $exemplos, $imagem, $ativo, $pid]);
        $msg = 'Família atualizada!';
        $acao = 'listar';
      }
    }
  }

  if ($acao === 'deletar' && $id) {
    $pdo->prepare("DELETE FROM familias WHERE id=?")->execute([$id]);
    $msg = 'Família excluída.';
    $acao = 'listar';
  }

  $familia_edit = null;
  if ($acao === 'editar' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM familias WHERE id=?");
    $stmt->execute([$id]);
    $familia_edit = $stmt->fetch();
    if (!$familia_edit) $acao = 'listar';
  }
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
      <a href="familias.php" class="active">Famílias</a>
      <a href="chaves.php">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Famílias</h1>
      <?php if ($acao === 'listar'): ?>
        <a href="?acao=novo<?= $filtro_ordem ? "&ordem_id=$filtro_ordem" : '' ?>" class="btn-primary">+ Nova Família</a>
      <?php else: ?>
        <a href="familias.php" class="btn-secondary">Voltar à Lista</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <?php if ($acao === 'listar'):
        $where = $filtro_ordem ? "WHERE f.ordem_id=$filtro_ordem" : "";
        $familias = $pdo->query("SELECT f.*, o.nome AS ordem_nome FROM familias f JOIN ordens o ON o.id=f.ordem_id $where ORDER BY o.ordem_exibicao, f.nome")->fetchAll();
      ?>
        <div class="filter-bar">
          <span style="font-size:0.9rem;color:var(--texto-suave)">Filtrar por ordem:</span>
          <select onchange="location.href='familias.php?ordem_id='+this.value">
            <option value="0" <?= !$filtro_ordem ? 'selected' : '' ?>>Todas</option>
            <?php foreach ($ordens as $o): ?>
              <option value="<?= $o['id'] ?>" <?= $filtro_ordem == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
            <?php endforeach; ?>
          </select>
          <span style="font-size:0.88rem;color:var(--texto-suave)"><?= count($familias) ?> famílias</span>
        </div>
        <div class="card">
          <table>
            <thead>
              <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Ordem</th>
                <th>Exemplos</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($familias as $f): ?>
                <tr>
                  <td><?= $f['imagem'] ? "<img src='../{$f['imagem']}' class='thumb'>" : "<div class='thumb-ph'>ImagemAqui</div>" ?></td>
                  <td><em><?= htmlspecialchars($f['nome']) ?></em></td>
                  <td><span class="tag-ordem"><?= htmlspecialchars($f['ordem_nome']) ?></span></td>
                  <td style="color:var(--texto-suave);font-size:0.88rem"><?= htmlspecialchars(mb_strimwidth($f['exemplos'] ?? '', 0, 50, '…')) ?></td>
                  <td>
                    <a href="?acao=editar&id=<?= $f['id'] ?>" class="btn-sm btn-edit">Editar</a>
                    <a href="?acao=deletar&id=<?= $f['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Excluir?')">Excluir</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php elseif ($acao === 'novo' || $acao === 'editar'):
        $e = $familia_edit ?: [];
        $preOrdem = $filtro_ordem ?: ($e['ordem_id'] ?? 0);
      ?>
        <div class="card">
          <div class="card-header">
            <h3><?= $acao === 'novo' ? 'Nova Família' : 'Editar: ' . htmlspecialchars($e['nome'] ?? '') ?></h3>
          </div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="form_acao" value="<?= $acao ?>">
              <?php if ($acao === 'editar'): ?>
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($e['imagem'] ?? '') ?>">
              <?php endif; ?>

              <div class="form-row">
                <div class="form-group">
                  <label class="lbl">Ordem</label>
                  <select name="ordem_id" class="form-control" required>
                    <option value="">Selecione.</option>
                    <?php foreach ($ordens as $o): ?>
                      <option value="<?= $o['id'] ?>" <?= $preOrdem == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="lbl">Nome da Família</label>
                  <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($e['nome'] ?? '') ?>" placeholder="Ex: Cicadidae">
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Descrição</label>
                <textarea name="descricao" class="form-control"><?= htmlspecialchars($e['descricao'] ?? '') ?></textarea>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="lbl">Exemplos</label>
                  <input type="text" name="exemplos" class="form-control" value="<?= htmlspecialchars($e['exemplos'] ?? '') ?>" placeholder="Cigarras, cigarrinhas">
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:2px">
                  <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:0.92rem">
                    <input type="checkbox" name="ativo" <?= ($e['ativo'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:var(--verde)">
                    Família ativa
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Imagem</label>
                <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImg(this)">
                <p class="hint">JPG, PNG ou WebP - máx 5MB<?= $e['imagem'] ?? '' ? '. Atual: <em>' . basename($e['imagem']) . '</em>' : '' ?></p>
                <?php if (!empty($e['imagem'])): ?>
                  <img src="../<?= htmlspecialchars($e['imagem']) ?>" class="img-preview" style="display:block;max-width:180px;border-radius:10px;margin-top:10px">
                <?php else: ?>
                  <img id="imgPreview" class="img-preview" style="display:none;max-width:180px;border-radius:10px;margin-top:10px">
                <?php endif; ?>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar</button>
                <a href="familias.php" class="btn-secondary">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script>
    function previewImg(input) {
      const prev = document.getElementById('imgPreview');
      if (!prev) return;
      if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
          prev.src = e.target.result;
          prev.style.display = 'block'
        };
        r.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>