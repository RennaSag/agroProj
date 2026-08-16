<?php require_once '../includes/db.php';

// se ja esta logado vai direto pro admin
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$mensagens = [
    'suap_negado'         => 'Login com SUAP cancelado.',
    'suap_invalido'       => 'Sessão de login expirada ou inválida. Tente novamente.',
    'suap_token'          => 'Não foi possível validar sua conta no SUAP. Tente novamente.',
    'suap_perfil'         => 'Não foi possível obter seus dados do SUAP. Tente novamente.',
    'suap_nao_autorizado' => 'Sua conta do SUAP não tem permissão para acessar este painel.',
];
$erro = $mensagens[$_GET['erro'] ?? ''] ?? '';

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
    <p style="text-align: center; color: var(--texto-suave); font-size: 0.95rem; margin-bottom: 20px;">
      Acesse com sua conta institucional do SUAP.
    </p>
    <a href="suap_login.php" class="btn-login" style="display: block; text-align: center; text-decoration: none;">Entrar com SUAP</a>
    <div class="back-link"><a href="../index.php">Voltar ao site</a></div>
  </div>
</body>

</html>
