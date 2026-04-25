<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Entomologia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin-index.css">
</head>

<body>
  <?php
  requireAdmin();
  $pdo = getDB();

  $ordens = $pdo->query("SELECT * FROM ordens ORDER BY ordem_exibicao, id")->fetchAll();
  $totalOrdens = count($ordens);
  $totalFamilias = $pdo->query("SELECT COUNT(*) FROM familias")->fetchColumn();
  $totalPassos = $pdo->query("SELECT COUNT(*) FROM chave_passos")->fetchColumn();
  ?>

  <nav class="sidebar">
    <div class="sidebar-logo">
      <h2>Entomologia</h2>
      <p>Admin</p>
    </div>
    <div class="nav">
      <div class="nav-section">Principal</div>
      <a href="index.php" class="active">Dashboard</a>
      <a href="ordens.php">Ordens</a>
      <a href="familias.php">Famílias</a>
      <a href="chaves.php">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom">
      <a href="logout.php">Sair (<?= htmlspecialchars($_SESSION['admin_nome']) ?>)</a>
    </div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
      <span class="topbar-user">Olá, <?= htmlspecialchars($_SESSION['admin_nome']) ?></span>
    </div>
    <div class="content">
      <div class="kpi-grid">
        <div class="kpi">
          <div class="kpi-num"><?= $totalOrdens ?></div>
          <div class="kpi-label">Ordens cadastradas</div>
        </div>
        <div class="kpi">
          <div class="kpi-num"><?= $totalFamilias ?></div>
          <div class="kpi-label">Famílias cadastradas</div>
        </div>
        <div class="kpi">
          <div class="kpi-num"><?= $totalPassos ?></div>
          <div class="kpi-label">Passos de chaves</div>
        </div>
      </div>

      <div class="table-card">
        <div class="table-header">
          <h3>Ordens/Subordens</h3>
          <a href="ordens.php?acao=novo" class="btn-primary">Nova Ordem</a>
        </div>
        <table>
          <thead>
            <tr>
              <th>Imagem</th>
              <th>Nome</th>
              <th>Famílias</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ordens as $o):
              $nf = $pdo->prepare("SELECT COUNT(*) FROM familias WHERE ordem_id=?");
              $nf->execute([$o['id']]);
              $qtdFam = $nf->fetchColumn();
            ?>
              <tr>
                <td><?= $o['imagem'] ? "<img src='../{$o['imagem']}' class='thumb' alt=''>" : "<div class='thumb-placeholder'>ImagemAqui</div>" ?></td>
                <td><em><?= htmlspecialchars($o['nome']) ?></em></td>
                <td><?= $qtdFam ?></td>
                <td><span class="badge <?= $o['ativo'] ? 'badge-ativo' : 'badge-inativo' ?>"><?= $o['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
                <td style="display:flex;gap:8px;align-items:center">
                  <a href="ordens.php?acao=editar&id=<?= $o['id'] ?>" class="btn-sm btn-edit">Editar</a>
                  <a href="chaves.php?ordem_id=<?= $o['id'] ?>" class="btn-sm btn-edit">Chave</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>

