<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Entomologia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-index.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-responsive.css?v=20260527">
</head>

<body>
  <?php
  requireAdmin();
  $pdo = getDB();

  $ordens = $pdo->query("SELECT * FROM ordens ORDER BY ordem_exibicao, id")->fetchAll();
  $totalOrdens = count($ordens);
  $totalFamilias = $pdo->query("SELECT COUNT(*) FROM familias")->fetchColumn();
  $totalPassos = $pdo->query("SELECT COUNT(*) FROM chave_passos")->fetchColumn();
  $totalOrdensSemImagem = (int)$pdo->query("SELECT COUNT(*) FROM ordens WHERE imagem IS NULL OR TRIM(imagem) = ''")->fetchColumn();
  $totalFamiliasSemImagem = (int)$pdo->query("SELECT COUNT(*) FROM familias WHERE imagem IS NULL OR TRIM(imagem) = ''")->fetchColumn();
  $totalAlternativasSemImagem = (int)$pdo->query("
    SELECT COALESCE(SUM(
      CASE WHEN sim_imagem IS NULL OR TRIM(sim_imagem) = '' THEN 1 ELSE 0 END +
      CASE WHEN nao_imagem IS NULL OR TRIM(nao_imagem) = '' THEN 1 ELSE 0 END
    ), 0)
    FROM chave_passos
  ")->fetchColumn();
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
      <a href="configuracoes.php">Configurações</a>
      <a href="../index.php" target="_blank" rel="noopener">Ver Site</a>
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

      <section class="pending-section" aria-labelledby="pendingTitle">
        <h2 id="pendingTitle">Pendências de imagens</h2>
        <p>Itens que ainda precisam de acervo visual cadastrado.</p>
        <div class="pending-grid">
          <div class="pending-card">
            <span class="pending-number"><?= $totalOrdensSemImagem ?></span>
            <span class="pending-label">ordens sem imagem</span>
          </div>
          <div class="pending-card">
            <span class="pending-number"><?= $totalFamiliasSemImagem ?></span>
            <span class="pending-label">famílias sem imagem</span>
          </div>
          <div class="pending-card">
            <span class="pending-number"><?= $totalAlternativasSemImagem ?></span>
            <span class="pending-label">alternativas de chave sem imagem</span>
          </div>
        </div>
      </section>

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
                <td>
                  <?php if (!empty($o['imagem'])): ?>
                    <img src="../<?= htmlspecialchars($o['imagem']) ?>" class="thumb" alt="">
                  <?php else: ?>
                    <div class="missing-thumb" aria-label="Sem imagem"><span aria-hidden="true">▧</span>Sem imagem</div>
                  <?php endif; ?>
                </td>
                <td>
                  <em><?= htmlspecialchars($o['nome']) ?></em>
                  <?php if (empty($o['imagem'])): ?><span class="content-badge">Sem imagem</span><?php endif; ?>
                </td>
                <td><?= $qtdFam ?></td>
                <td><span class="badge <?= $o['ativo'] ? 'badge-ativo' : 'badge-inativo' ?>"><?= $o['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
                <td><div class="admin-table-actions">
                  <a href="ordens.php?acao=editar&id=<?= $o['id'] ?>" class="btn-sm btn-edit">Editar</a>
                  <a href="chaves.php?ordem_id=<?= $o['id'] ?>" class="btn-sm btn-edit">Chave</a>
                </div></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="../assets/js/admin-layout.js?v=20260527"></script>
</body>

</html>
