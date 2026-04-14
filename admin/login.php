<?php require_once '../includes/db.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Dashboard</title>
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
      --branco: #fff
    }

    body {
      font-family: 'Source Sans 3', sans-serif;
      background: var(--verde-bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px
    }

    .login-card {
      background: var(--branco);
      border-radius: 18px;
      padding: 48px 40px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 8px 40px rgba(42, 82, 40, 0.14);
      border: 1px solid var(--verde-borda)
    }

    .logo {
      text-align: center;
      margin-bottom: 32px
    }

    .logo h1 {
      font-family: 'Playfair Display', serif;
      color: var(--verde-escuro);
      font-size: 1.5rem;
      font-weight: 700
    }

    .logo p {
      color: #666;
      font-size: 0.88rem;
      margin-top: 6px
    }

    .form-group {
      margin-bottom: 20px
    }

    label {
      display: block;
      font-weight: 600;
      font-size: 0.88rem;
      color: var(--texto);
      margin-bottom: 7px;
      text-transform: uppercase;
      letter-spacing: 0.05em
    }

    input {
      width: 100%;
      padding: 13px 16px;
      border: 1.5px solid var(--verde-borda);
      border-radius: 10px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 0.97rem;
      color: var(--texto);
      transition: border 0.18s;
      background: var(--branco)
    }

    input:focus {
      outline: none;
      border-color: var(--verde)
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: var(--verde);
      color: var(--branco);
      border: none;
      border-radius: 10px;
      font-family: 'Source Sans 3', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.18s;
      margin-top: 8px
    }

    .btn-login:hover {
      background: var(--verde-escuro)
    }

    .alert {
      background: #fef0f0;
      border: 1px solid #f5c6c6;
      border-radius: 8px;
      padding: 12px 16px;
      color: #c0392b;
      font-size: 0.9rem;
      margin-bottom: 20px
    }

    .back-link {
      text-align: center;
      margin-top: 20px
    }

    .back-link a {
      color: var(--verde);
      text-decoration: none;
      font-size: 0.88rem
    }
  </style>
</head>

<body>
  <?php
  
  $erro = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $pdo = getDB();
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email=? OR nome=?");
    $stmt->execute([$email, $email]);

    $admin = $stmt->fetch();
    if ($admin && password_verify($senha, $admin['senha'])) {
      $_SESSION['admin_id'] = $admin['id'];
      $_SESSION['admin_nome'] = $admin['nome'];
      header('Location: index.php');
      exit;
    } else {
      $erro = 'E-mail ou senha incorretos.';
    }
  }
  ?>
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
        <label>E-mail</label>
        <input type="text" name="email" required placeholder="adm" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" name="senha" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn-login">Entrar</button>
    </form>
    <div class="back-link"><a href="../index.php">Voltar ao site</a></div>
  </div>
</body>

</html>