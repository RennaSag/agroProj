<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administradores</title>
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

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px
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
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.15s
    }

    .btn-primary:hover {
      background: var(--verde-escuro)
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

    .btn-del {
      background: #fef0f0;
      color: #c0392b;
      border: 1px solid #f5c6c6
    }

    .btn-del:hover {
      background: #f5c6c6
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
  $msg = '';
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (strlen($senha) < 6) {
      $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
      $hash = password_hash($senha, PASSWORD_DEFAULT);
      try {
        $stmt = $pdo->prepare("INSERT INTO admins (nome, email, senha) VALUES (?,?,?)");
        $stmt->execute([$nome, $email, $hash]);
        $msg = 'Administrador cadastrado com sucesso!';
      } catch (Exception $e) {
        $erro = 'E-mail já cadastrado.';
      }
    }
  }

  if (isset($_GET['del']) && (int)$_GET['del'] !== (int)$_SESSION['admin_id']) {
    $pdo->prepare("DELETE FROM admins WHERE id=?")->execute([(int)$_GET['del']]);
    $msg = 'Administrador removido.';
  }

  $admins = $pdo->query("SELECT id, nome, email, criado_em FROM admins ORDER BY id")->fetchAll();
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
      <a href="chaves.php">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php" class="active">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>
  <div class="main">
    <div class="topbar">
      <h1>Administradores</h1>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <div class="card">
        <div class="card-header">
          <h3>Usuários do painel</h3>
        </div>
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Cadastrado em</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($admins as $a): ?>
              <tr>
                <td><?= htmlspecialchars($a['nome']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
                <td style="color:var(--texto-suave)"><?= date('d/m/Y', strtotime($a['criado_em'])) ?></td>
                <td>
                  <?php if ($a['id'] !== (int)$_SESSION['admin_id']): ?>
                    <a href="?del=<?= $a['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Remover este admin?')">🗑 Remover</a>
                  <?php else: ?>
                    <span style="font-size:0.82rem;color:var(--texto-suave)">(você)</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Novo Administrador</h3>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="form-row">
              <div class="form-group">
                <label class="lbl">Nome</label>
                <input type="text" name="nome" class="form-control" required placeholder="Ex: Prof. Silva">
              </div>
              <div class="form-group">
                <label class="lbl">E-mail</label>
                <input type="email" name="email" class="form-control" required placeholder="prof@ufla.br">
              </div>
            </div>
            <div class="form-group">
              <label class="lbl">Senha</label>
              <input type="password" name="senha" class="form-control" required minlength="6">
              <p class="hint">Mínimo 6 caracteres.</p>
            </div>
            <button type="submit" class="btn-primary">Cadastrar Admin</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>