<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administradores</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin-admins.css">
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
        $erro = 'E-mail jÃ¡ cadastrado.';
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
      <a href="familias.php">FamÃ­lias</a>
      <a href="chaves.php">Chaves DicotÃ´micas</a>
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
          <h3>UsuÃ¡rios do painel</h3>
        </div>
        <table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Cadastrado em</th>
              <th>AÃ§Ãµes</th>
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
                    <a href="?del=<?= $a['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Remover este admin?')">ðŸ—‘ Remover</a>
                  <?php else: ?>
                    <span style="font-size:0.82rem;color:var(--texto-suave)">(vocÃª)</span>
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
              <p class="hint">MÃ­nimo 6 caracteres.</p>
            </div>
            <button type="submit" class="btn-primary">Cadastrar Admin</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
