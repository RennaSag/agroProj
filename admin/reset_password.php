<?php require_once '../includes/db.php';

// Se já está logado vai direto pro admin
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$email = $_GET['email'] ?? '';
$tokenRaw = $_GET['token'] ?? '';
$erro = '';
$sucesso = '';
$tokenValido = false;

if (empty($email) || empty($tokenRaw)) {
    $erro = 'Link de recuperação inválido ou incompleto.';
} else {
    $pdo = getDB();
    ensurePasswordResetsTable($pdo);

    // Busca o token do banco
    $stmt = $pdo->prepare("SELECT token_hash, expira_em FROM password_resets WHERE email = ?");
    $stmt->execute([$email]);
    $resetData = $stmt->fetch();

    if ($resetData) {
        // Verifica se o tempo expirou
        if (strtotime($resetData['expira_em']) < time()) {
            $erro = 'O link de recuperação expirou. Por favor, solicite um novo.';
        } else {
            // Verifica se o token bate com o hash (simulando password_verify no token raw)
            if (password_verify($tokenRaw, $resetData['token_hash'])) {
                $tokenValido = true;
            } else {
                $erro = 'Link de recuperação inválido.';
            }
        }
    } else {
        $erro = 'Link de recuperação inválido ou já utilizado.';
    }
}

if ($tokenValido && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    if (strlen($novaSenha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($novaSenha !== $confirmaSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        // Hash the new password
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Update admin table
        $updateStmt = $pdo->prepare("UPDATE admins SET senha = ? WHERE email = ?");
        $updateStmt->execute([$senhaHash, $email]);

        // Delete the used token
        $delStmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $delStmt->execute([$email]);

        $sucesso = 'Senha alterada com sucesso! Você já pode fazer o login com a nova senha.';
        $tokenValido = false; // To hide the form
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha - Painel Administrativo</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-login.css?v=20260527">
</head>

<body>
  <div class="login-card">
    <div class="logo">
      <h1>Entomologia</h1>
      <p>Redefinir Senha</p>
    </div>
    
    <?php if ($erro): ?>
      <div class="alert" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
      <div class="alert" style="background-color: var(--verde-claro); color: var(--verde-escuro); border: 1px solid var(--verde-borda);">
        <?= htmlspecialchars($sucesso) ?>
      </div>
      <div style="text-align: center; margin-top: 20px;">
        <a href="login.php" class="btn-primary" style="text-decoration: none; display: inline-block;">Ir para o Login</a>
      </div>
    <?php endif; ?>

    <?php if ($tokenValido): ?>
      <p style="text-align: center; color: var(--texto-suave); font-size: 0.95rem; margin-bottom: 20px;">
        Defina uma nova senha para sua conta.
      </p>

      <form method="POST">
        <div class="form-group">
          <label for="nova_senha">Nova Senha</label>
          <input id="nova_senha" type="password" name="nova_senha" required placeholder="Pelo menos 6 caracteres">
        </div>
        <div class="form-group">
          <label for="confirma_senha">Confirmar Nova Senha</label>
          <input id="confirma_senha" type="password" name="confirma_senha" required placeholder="Repita a senha">
        </div>
        <button type="submit" class="btn-login">Salvar Senha</button>
      </form>
    <?php elseif (!$sucesso): ?>
      <div class="back-link" style="text-align: center; margin-top: 20px;">
        <a href="forgot_password.php">Solicitar um novo link</a>
      </div>
    <?php endif; ?>

    <?php if (!$sucesso && !$tokenValido): ?>
      <div class="back-link">
        <a href="login.php" style="margin-right: 15px;">&larr; Voltar ao Login</a>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>
