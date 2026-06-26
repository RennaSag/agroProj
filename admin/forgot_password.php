<?php require_once '../includes/db.php';

// Se já está logado vai direto pro admin
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pdo = getDB();

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Verifica se o e-mail existe
        $stmt = $pdo->prepare("SELECT id, nome FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin) {
            ensurePasswordResetsTable($pdo);

            // Gera um token seguro
            $tokenRaw = bin2hex(random_bytes(32));
            $tokenHash = password_hash($tokenRaw, PASSWORD_DEFAULT);

            // Deleta tokens antigos desse e-mail
            $delStmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $delStmt->execute([$email]);

            // Gera data de expiração no PHP (1 hora a partir de agora)
            $expiraEm = date('Y-m-d H:i:s', time() + 3600);

            // Insere novo token com validade de 1 hora
            $insStmt = $pdo->prepare("
                INSERT INTO password_resets (email, token_hash, expira_em)
                VALUES (?, ?, ?)
            ");
            $insStmt->execute([$email, $tokenHash, $expiraEm]);

            // Prepara o link
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            $link = $protocol . $domainName . $baseDir . "/reset_password.php?email=" . urlencode($email) . "&token=" . $tokenRaw;

            // Envio de e-mail nativo
            $assunto = "Recuperação de Senha - Entomologia";
            $corpo = "Olá " . $admin['nome'] . ",\n\nVocê solicitou a recuperação de senha.\n";
            $corpo .= "Clique no link abaixo para redefinir sua senha (válido por 1 hora):\n\n";
            $corpo .= $link . "\n\nSe você não solicitou, ignore este e-mail.";
            $headers = "From: noreply@" . $domainName . "\r\n";
            
            mail($email, $assunto, $corpo, $headers); 
        }

        // Mensagem genérica de sucesso para evitar enumeração de e-mails
        $mensagem = 'Se o e-mail informado estiver cadastrado, você receberá um link de recuperação em breve.';
    } else {
        $mensagem = 'Por favor, insira um e-mail válido.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Senha - Painel Administrativo</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-login.css?v=20260527">
</head>

<body>
  <div class="login-card">
    <div class="logo">
      <h1>Entomologia</h1>
      <p>Recuperar Senha</p>
    </div>
    
    <?php if ($mensagem): ?>
      <div class="alert" style="background-color: var(--verde-claro); color: var(--verde-escuro); border: 1px solid var(--verde-borda);">
        <?= htmlspecialchars($mensagem) ?>
      </div>
    <?php endif; ?>

    <p style="text-align: center; color: var(--texto-suave); font-size: 0.95rem; margin-bottom: 20px;">
      Informe o seu e-mail cadastrado e enviaremos instruções para redefinir sua senha.
    </p>

    <form method="POST">
      <div class="form-group">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" autocomplete="username" required placeholder="professor@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <button type="submit" class="btn-login">Enviar Link</button>
    </form>
    <div class="back-link">
      <a href="login.php" style="margin-right: 15px;">&larr; Voltar ao Login</a>
      <a href="../index.php">Ir para o site</a>
    </div>
  </div>
</body>

</html>
