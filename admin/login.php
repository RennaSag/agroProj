<?php require_once '../includes/db.php';

// se ja esta logado vai direto pro admin
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=? OR nome=?");
    $stmt->execute([$email, $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($senha, $admin['senha'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_nome'] = $admin['nome'];
        header('Location: index.php');
        exit;
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Painel Administrativo</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-login.css?v=20260527">
</head>

<body>
  <div class="login-card">
    <div class="logo">
      <h1>Entomologia</h1>
      <p>Painel Administrativo</p>
    </div>
    <?php if ($erro): ?>
      <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" autocomplete="username" required placeholder="professor@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" autocomplete="current-password" required placeholder="********">
        <div style="text-align: right; margin-top: 5px;">
          <a href="forgot_password.php" style="font-size: 0.85rem; color: var(--verde); text-decoration: none;">Esqueci minha senha</a>
        </div>
      </div>
      <button type="submit" class="btn-login">Entrar</button>
    </form>
    <div class="back-link"><a href="../index.php">Voltar ao site</a></div>
  </div>
</body>

</html>
