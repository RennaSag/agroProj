<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configurações - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-admins.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-responsive.css?v=20260601">
</head>

<body>
  <?php
  $pdo = getDB();
  ensureConfiguracoesTable($pdo);
  $msg = '';
  $erro = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = isset($_POST['exibir_miniaturas_historico']) ? '1' : '0';
    try {
      setConfiguracao(
        'exibir_miniaturas_historico',
        $valor,
        'Exibir miniaturas das opções no histórico da identificação'
      );
      $msg = 'Configurações salvas com sucesso.';
    } catch (Exception $e) {
      $erro = 'Não foi possível salvar as configurações.';
    }
  }

  $exibirMiniaturas = configuracaoAtiva('exibir_miniaturas_historico', true);
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
      <a href="admins.php">Administradores</a>
      <a href="configuracoes.php" class="active">Configurações</a>
      <a href="../index.php" target="_blank" rel="noopener">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Configurações</h1>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <div class="card">
        <div class="card-header">
          <h3>Chave dicotômica</h3>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="settings-option">
              <label for="exibir_miniaturas_historico" class="settings-label">
                <input
                  type="checkbox"
                  id="exibir_miniaturas_historico"
                  name="exibir_miniaturas_historico"
                  value="1"
                  <?= $exibirMiniaturas ? 'checked' : '' ?>
                >
                <span>
                  <strong>Exibir miniaturas no histórico da identificação</strong>
                  <small>Quando ativado, cada escolha feita na chave mostra uma miniatura da opção selecionada. O padrão do sistema é ativado.</small>
                </span>
              </label>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-primary">Salvar configurações</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/admin-layout.js?v=20260527"></script>
</body>

</html>
