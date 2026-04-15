<?php require_once '../includes/db.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Ordens</title>
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
      padding: 0;
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

    .form-group label {
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
      min-height: 100px;
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
      gap: 6px;
      transition: all 0.15s
    }

    .btn-secondary:hover {
      background: var(--verde-borda)
    }

    .btn-danger {
      background: #fef0f0;
      color: #c0392b;
      border: 1px solid #f5c6c6;
      padding: 10px 20px;
      border-radius: 9px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s
    }

    .btn-danger:hover {
      background: #f5c6c6
    }

    .form-actions {
      display: flex;
      gap: 12px;
      align-items: center;
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

    .badge-ativo {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 600;
      background: #d4edda;
      color: #1a6b2e
    }

    .badge-inativo {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 600;
      background: #f8d7da;
      color: #721c24
    }

    .img-preview {
      max-width: 200px;
      border-radius: 10px;
      margin-top: 10px;
      display: none
    }

    .hint {
      font-size: 0.82rem;
      color: var(--texto-suave);
      margin-top: 5px
    }
  </style>
</head>

<body>
  <?php
  requireAdmin();
  $pdo = getDB();
  $acao = $_GET['acao'] ?? 'listar';
  $id = (int)($_GET['id'] ?? 0);
  $msg = '';
  $erro = '';

  // PROCESSAR FORM
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $caracteristicas_raw = trim($_POST['caracteristicas'] ?? '');
    $exemplos = trim($_POST['exemplos'] ?? '');
    $importancia = trim($_POST['importancia_agricola'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $ordem_exibicao = (int)($_POST['ordem_exibicao'] ?? 0);

    // Converte características (uma por linha) em JSON
    $caract_arr = array_filter(array_map('trim', explode("\n", $caracteristicas_raw)));
    $caracteristicas = json_encode(array_values($caract_arr), JSON_UNESCAPED_UNICODE);

    // Upload imagem
    $imagem = $_POST['imagem_atual'] ?? '';
    if (!empty($_FILES['imagem']['tmp_name'])) {
      $upload = uploadImagem($_FILES['imagem'], 'ordem');
      if (is_string($upload)) $imagem = $upload;
      elseif (is_array($upload)) $erro = $upload['error'];
    }

    if (!$erro) {
      if ($_POST['form_acao'] === 'novo') {
        $stmt = $pdo->prepare("INSERT INTO ordens (nome,descricao,caracteristicas,exemplos,importancia_agricola,imagem,ativo,ordem_exibicao) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$nome, $descricao, $caracteristicas, $exemplos, $importancia, $imagem, $ativo, $ordem_exibicao]);
        $msg = 'Ordem cadastrada com sucesso!';
        $acao = 'listar';
      } elseif ($_POST['form_acao'] === 'editar') {
        $pid = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE ordens SET nome=?,descricao=?,caracteristicas=?,exemplos=?,importancia_agricola=?,imagem=?,ativo=?,ordem_exibicao=? WHERE id=?");
        $stmt->execute([$nome, $descricao, $caracteristicas, $exemplos, $importancia, $imagem, $ativo, $ordem_exibicao, $pid]);
        $msg = 'Ordem atualizada com sucesso!';
        $acao = 'listar';
      }
    }
  }

  // DELETE
  if ($acao === 'deletar' && $id) {
    $pdo->prepare("DELETE FROM ordens WHERE id=?")->execute([$id]);
    $msg = 'Ordem excluída.';
    $acao = 'listar';
  }

  // Carrega para edição
  $ordem_edit = null;
  if ($acao === 'editar' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM ordens WHERE id=?");
    $stmt->execute([$id]);
    $ordem_edit = $stmt->fetch();
    if (!$ordem_edit) {
      $acao = 'listar';
    }
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
      <a href="ordens.php" class="active">Ordens</a>
      <a href="familias.php">Famílias</a>
      <a href="chaves.php">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">← Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Ordens/Subordens</h1>
      <?php if ($acao === 'listar'): ?>
        <a href="?acao=novo" class="btn-primary">Nova Ordem</a>
      <?php else: ?>
        <a href="ordens.php" class="btn-secondary">Voltar à Lista</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <?php if ($acao === 'listar'): ?>
        <div class="card">
          <div class="card-header">
            <h3>Todas as Ordens</h3>
          </div>
          <table>
            <thead>
              <tr>
                <th>Img</th>
                <th>Nome</th>
                <th>Ordem</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pdo->query("SELECT * FROM ordens ORDER BY ordem_exibicao,id")->fetchAll() as $o): ?>
                <tr>
                  <td><?= $o['imagem'] ? "<img src='../{$o['imagem']}' class='thumb'>" : "<div class='thumb-ph'>ImagemAqui</div>" ?></td>
                  <td><em><?= htmlspecialchars($o['nome']) ?></em></td>
                  <td><?= $o['ordem_exibicao'] ?></td>
                  <td><span class="<?= $o['ativo'] ? 'badge-ativo' : 'badge-inativo' ?>"><?= $o['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
                  <td>
                    <a href="?acao=editar&id=<?= $o['id'] ?>" class="btn-sm btn-edit">Editar</a>
                    <a href="?acao=deletar&id=<?= $o['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Excluir esta ordem e todas suas famílias/chaves?')">Excluir</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php elseif ($acao === 'novo' || $acao === 'editar'):
        $e = $ordem_edit ?: [];
        $caract_txt = '';
        if (!empty($e['caracteristicas'])) {
          $arr = json_decode($e['caracteristicas'], true);
          if (is_array($arr)) $caract_txt = implode("\n", $arr);
        }
      ?>
        <div class="card">
          <div class="card-header">
            <h3><?= $acao === 'novo' ? 'Nova Ordem' : 'Editar: ' . htmlspecialchars($e['nome'] ?? '') ?></h3>
          </div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="form_acao" value="<?= $acao ?>">
              <?php if ($acao === 'editar'): ?><input type="hidden" name="id" value="<?= $e['id'] ?>"><input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($e['imagem'] ?? '') ?>"><?php endif; ?>

              <div class="form-row">
                <div class="form-group">
                  <label>Nome</label>
                  <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($e['nome'] ?? '') ?>" placeholder="Ex: Hemiptera-Auchenorrhyncha">
                </div>
                <div class="form-group">
                  <label>Ordem de Exibição</label>
                  <input type="number" name="ordem_exibicao" class="form-control" value="<?= $e['ordem_exibicao'] ?? 0 ?>" min="0">
                </div>
              </div>

              <div class="form-group">
                <label>Descrição Geral</label>
                <textarea name="descricao" class="form-control"><?= htmlspecialchars($e['descricao'] ?? '') ?></textarea>
              </div>

              <div class="form-group">
                <label>Características (uma por linha)</label>
                <textarea name="caracteristicas" class="form-control" placeholder="Peças bucais picadoras-sugadoras&#10;Antenas curtas e setáceas&#10;..."><?= htmlspecialchars($caract_txt) ?></textarea>
                <p class="hint">Cada linha vira um item na lista de características.</p>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Exemplos</label>
                  <input type="text" name="exemplos" class="form-control" value="<?= htmlspecialchars($e['exemplos'] ?? '') ?>" placeholder="Cigarras, cigarrinhas">
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:2px">
                  <label style="display:flex;align-items:center;gap:10px;text-transform:none;letter-spacing:0;font-size:0.92rem;cursor:pointer">
                    <input type="checkbox" name="ativo" <?= ($e['ativo'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:var(--verde)">
                    Ordem ativa (visível no site)
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label>Importância Agrícola</label>
                <textarea name="importancia_agricola" class="form-control"><?= htmlspecialchars($e['importancia_agricola'] ?? '') ?></textarea>
              </div>

              <div class="form-group">
                <label>Imagem</label>
                <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImg(this)">
                <p class="hint">JPG, PNG ou WebP - máx 5MB. <?= $e['imagem'] ?? '' ? 'Imagem atual: <em>' . basename($e['imagem']) . '</em>' : '' ?></p>
                <?php if (!empty($e['imagem'])): ?>
                  <img src="../<?= htmlspecialchars($e['imagem']) ?>" class="img-preview" style="display:block">
                <?php else: ?>
                  <img id="imgPreview" class="img-preview">
                <?php endif; ?>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar</button>
                <a href="ordens.php" class="btn-secondary">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script>
    function previewImg(input) {
      const prev = document.getElementById('imgPreview') || document.querySelector('.img-preview');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          prev.src = e.target.result;
          prev.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>