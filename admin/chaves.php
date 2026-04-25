<?php require_once '../includes/db.php';
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chaves DicotÃ´micas - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin-chaves.css">
</head>

<body>
  <?php
  
  requireAdmin();
  $pdo = getDB();

  $acao = $_GET['acao'] ?? 'listar';
  $ordem_id = (int)($_GET['ordem_id'] ?? 0);
  $passo_id = (int)($_GET['passo_id'] ?? 0);
  $msg = '';
  $erro = '';

  $ordens = $pdo->query("SELECT id, nome FROM ordens WHERE ativo=1 ORDER BY ordem_exibicao, id")->fetchAll();

  function getFamilias($pdo, $ordem_id)
  {
    if (!$ordem_id) return [];
    $stmt = $pdo->prepare("SELECT id, nome FROM familias WHERE ordem_id=? AND ativo=1 ORDER BY nome");
    $stmt->execute([$ordem_id]);
    return $stmt->fetchAll();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oid = (int)$_POST['ordem_id'];
    $pergunta = trim($_POST['pergunta'] ?? '');
    $passo_num = (int)$_POST['passo_numero'];
    $sim_texto = trim($_POST['opcao_sim_texto'] ?? '');
    $nao_texto = trim($_POST['opcao_nao_texto'] ?? '');
    $sim_passo = $_POST['sim_leva_passo'] !== '' ? (int)$_POST['sim_leva_passo'] : null;
    $nao_passo = $_POST['nao_leva_passo'] !== '' ? (int)$_POST['nao_leva_passo'] : null;
    $sim_fam = $_POST['sim_resultado_familia_id'] !== '' ? (int)$_POST['sim_resultado_familia_id'] : null;
    $nao_fam = $_POST['nao_resultado_familia_id'] !== '' ? (int)$_POST['nao_resultado_familia_id'] : null;

    if ($_POST['form_acao'] === 'novo_passo') {
      $stmt = $pdo->prepare("INSERT INTO chave_passos (ordem_id,passo_numero,pergunta,opcao_sim_texto,opcao_nao_texto,sim_leva_passo,nao_leva_passo,sim_resultado_familia_id,nao_resultado_familia_id) VALUES (?,?,?,?,?,?,?,?,?)");
      $stmt->execute([$oid, $passo_num, $pergunta, $sim_texto, $nao_texto, $sim_passo, $nao_passo, $sim_fam, $nao_fam]);
      $msg = 'Passo adicionado!';
    } elseif ($_POST['form_acao'] === 'editar_passo') {
      $pid = (int)$_POST['passo_id'];
      $stmt = $pdo->prepare("UPDATE chave_passos SET passo_numero=?,pergunta=?,opcao_sim_texto=?,opcao_nao_texto=?,sim_leva_passo=?,nao_leva_passo=?,sim_resultado_familia_id=?,nao_resultado_familia_id=? WHERE id=?");
      $stmt->execute([$passo_num, $pergunta, $sim_texto, $nao_texto, $sim_passo, $nao_passo, $sim_fam, $nao_fam, $pid]);
      $msg = 'Passo atualizado!';
    }
    $acao = 'listar';
  }

  if ($acao === 'del_passo' && $passo_id) {
    $pdo->prepare("DELETE FROM chave_passos WHERE id=?")->execute([$passo_id]);
    $msg = 'Passo excluÃ­do.';
    $acao = 'listar';
  }

  $passo_edit = null;
  if ($acao === 'editar' && $passo_id) {
    $stmt = $pdo->prepare("SELECT * FROM chave_passos WHERE id=?");
    $stmt->execute([$passo_id]);
    $passo_edit = $stmt->fetch();
    if ($passo_edit) $ordem_id = $passo_edit['ordem_id'];
  }

  $passos = [];
  if ($ordem_id) {
    $stmt = $pdo->prepare("
        SELECT cp.*, 
               fs.nome AS sim_fam_nome, fn.nome AS nao_fam_nome
        FROM chave_passos cp
        LEFT JOIN familias fs ON fs.id = cp.sim_resultado_familia_id
        LEFT JOIN familias fn ON fn.id = cp.nao_resultado_familia_id
        WHERE cp.ordem_id = ?
        ORDER BY cp.passo_numero
    ");
    $stmt->execute([$ordem_id]);
    $passos = $stmt->fetchAll();
  }

  $familias = getFamilias($pdo, $ordem_id);
  $prox_passo = 1;
  if ($passos) $prox_passo = max(array_column($passos, 'passo_numero')) + 1;
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
      <a href="chaves.php" class="active">Chaves DicotÃ´micas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Chaves DicotÃ´micas</h1>
      <?php if ($ordem_id): ?>
        <a href="../chave.php?ordem=<?= $ordem_id ?>" target="_blank" class="btn-secondary">Ver Chave no Site</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      
      <div class="card">
        <div class="card-header">
          <h3>Selecionar Ordem para Editar Chave</h3>
        </div>
        <div class="card-body" style="padding:20px 28px">
          <select class="ordem-select" onchange="location.href='chaves.php?ordem_id='+this.value">
            <option value="0">Selecione uma ordem.</option>
            <?php foreach ($ordens as $o): ?>
              <option value="<?= $o['id'] ?>" <?= $ordem_id == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if ($ordem_id):
        $ordem_atual = $pdo->prepare("SELECT nome FROM ordens WHERE id=?");
        $ordem_atual->execute([$ordem_id]);
        $oNome = $ordem_atual->fetchColumn();
      ?>

      
        <div class="card">
          <div class="card-header">
            <h3>Passos da Chave: <em><?= htmlspecialchars($oNome) ?></em></h3>
            <span style="font-size:0.85rem;color:var(--texto-suave)"><?= count($passos) ?> passos cadastrados</span>
          </div>
          <?php if ($passos): ?>
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Pergunta</th>
                  <th>SIM</th>
                  <th>NÃƒO</th>
                  <th>AÃ§Ãµes</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($passos as $p): ?>
                  <tr>
                    <td><span class="passo-num"><?= $p['passo_numero'] ?></span></td>
                    <td style="max-width:300px"><?= htmlspecialchars(mb_strimwidth($p['pergunta'], 0, 80, 'â€¦')) ?></td>
                    <td style="font-size:0.85rem">
                      <?php if ($p['sim_resultado_familia_id']): ?>
                        <strong style="color:var(--verde)"><em><?= htmlspecialchars($p['sim_fam_nome']) ?></em></strong>
                      <?php elseif ($p['sim_leva_passo']): ?>
                        Passo <?= $p['sim_leva_passo'] ?>
                      <?php else: echo 'â€”';
                      endif; ?>
                    </td>
                    <td style="font-size:0.85rem">
                      <?php if ($p['nao_resultado_familia_id']): ?>
                        <strong style="color:#c0392b"><em><?= htmlspecialchars($p['nao_fam_nome']) ?></em></strong>
                      <?php elseif ($p['nao_leva_passo']): ?>
                      Passo <?= $p['nao_leva_passo'] ?>
                      <?php else: echo 'â€”';
                      endif; ?>
                    </td>
                    <td>
                      <a href="?acao=editar&passo_id=<?= $p['id'] ?>&ordem_id=<?= $ordem_id ?>" class="btn-sm btn-edit">Editar</a>
                      <a href="?acao=del_passo&passo_id=<?= $p['id'] ?>&ordem_id=<?= $ordem_id ?>" class="btn-sm btn-del" onclick="return confirm('Excluir este passo?')">Excluir</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div style="padding:32px;text-align:center;color:var(--texto-suave)">Nenhum passo cadastrado para esta ordem.</div>
          <?php endif; ?>
        </div>

       
        <div class="card">
          <div class="card-header">
            <h3><?= ($acao === 'editar' && $passo_edit) ? 'Editar Passo ' . $passo_edit['passo_numero'] : 'Adicionar Novo Passo' ?></h3>
          </div>
          <div class="card-body">
            <form method="POST">
              <input type="hidden" name="form_acao" value="<?= ($acao === 'editar' && $passo_edit) ? 'editar_passo' : 'novo_passo' ?>">
              <input type="hidden" name="ordem_id" value="<?= $ordem_id ?>">
              <?php if ($acao === 'editar' && $passo_edit): ?>
                <input type="hidden" name="passo_id" value="<?= $passo_edit['id'] ?>">
              <?php endif; ?>
              <?php $pe = $passo_edit ?: []; ?>

              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">NÃºmero do Passo</label>
                  <input type="number" name="passo_numero" class="form-control" required min="1" value="<?= $pe['passo_numero'] ?? $prox_passo ?>">
                  <p class="hint">Passos sÃ£o exibidos em ordem crescente.</p>
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Pergunta DicotÃ´mica</label>
                <textarea name="pergunta" class="form-control" required placeholder="Ex: Inseto de tamanho grande (>2cm) com Ã³rgÃ£o estridulador nos machos?"><?= htmlspecialchars($pe['pergunta'] ?? '') ?></textarea>
              </div>

              <div class="divider"></div>
              <div class="section-label">Resposta SIM</div>
              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">Texto da opÃ§Ã£o SIM</label>
                  <input type="text" name="opcao_sim_texto" class="form-control" value="<?= htmlspecialchars($pe['opcao_sim_texto'] ?? '') ?>" placeholder="DescriÃ§Ã£o breve da caracterÃ­stica SIM">
                </div>
              </div>
              <div class="resultado-box" style="margin-bottom:20px">
                <div class="form-row-3">
                  <div class="form-group" style="margin-bottom:0">
                    <label class="lbl">Sim: PrÃ³ximo passo</label>
                    <input type="number" name="sim_leva_passo" class="form-control" min="1" value="<?= $pe['sim_leva_passo'] ?? '' ?>" placeholder="NÂ° do passo">
                    <p class="hint">Deixe vazio se leva a uma famÃ­lia.</p>
                  </div>
                  <div class="form-group" style="margin-bottom:0;grid-column:span 2">
                    <label class="lbl">SIM: Resultado (famÃ­lia)</label>
                    <select name="sim_resultado_familia_id" class="form-control">
                      <option value="">NÃ£o Ã© resultado final</option>
                      <?php foreach ($familias as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= ($pe['sim_resultado_familia_id'] ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="divider"></div>
              <div class="section-label" style="color:#c0392b">Resposta NÃƒO</div>
              <div class="form-row" style="margin-bottom:20px">
                <div class="form-group" style="margin-bottom:0">
                  <label class="lbl">Texto da opÃ§Ã£o NÃƒO</label>
                  <input type="text" name="opcao_nao_texto" class="form-control" value="<?= htmlspecialchars($pe['opcao_nao_texto'] ?? '') ?>" placeholder="DescriÃ§Ã£o breve da caracterÃ­stica NÃƒO">
                </div>
              </div>
              <div class="resultado-box" style="margin-bottom:20px">
                <div class="form-row-3">
                  <div class="form-group" style="margin-bottom:0">
                    <label class="lbl">NÃƒO: PrÃ³ximo passo</label>
                    <input type="number" name="nao_leva_passo" class="form-control" min="1" value="<?= $pe['nao_leva_passo'] ?? '' ?>" placeholder="NÂ° do passo">
                  </div>
                  <div class="form-group" style="margin-bottom:0;grid-column:span 2">
                    <label class="lbl">NÃƒO: Resultado (famÃ­lia)</label>
                    <select name="nao_resultado_familia_id" class="form-control">
                      <option value="">NÃ£o Ã© resultado final</option>
                      <?php foreach ($familias as $f): ?>
                        <option value="<?= $f['id'] ?>" <?= ($pe['nao_resultado_familia_id'] ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Passo</button>
                <?php if ($acao === 'editar'): ?>
                  <a href="chaves.php?ordem_id=<?= $ordem_id ?>" class="btn-secondary">Cancelar</a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>

      <?php endif; ?>
    </div>
  </div>
</body>

</html>
