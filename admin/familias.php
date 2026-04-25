<?php require_once '../includes/db.php'; 
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FamÃ­lias</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin-familias.css">
</head>

<body>
  <?php
  
  requireAdmin();
  $pdo = getDB();
  $acao = $_GET['acao'] ?? 'listar';
  $id = (int)($_GET['id'] ?? 0);
  $filtro_ordem = (int)($_GET['ordem_id'] ?? 0);
  $msg = '';
  $erro = '';

 
  $ordens = $pdo->query("SELECT id, nome FROM ordens WHERE ativo=1 ORDER BY ordem_exibicao, id")->fetchAll();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $ordem_id = (int)($_POST['ordem_id'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $exemplos = trim($_POST['exemplos'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    $imagem = $_POST['imagem_atual'] ?? '';
    if (!empty($_FILES['imagem']['tmp_name'])) {
      $upload = uploadImagem($_FILES['imagem'], 'familia');
      if (is_string($upload)) $imagem = $upload;
      elseif (is_array($upload)) $erro = $upload['error'];
    }

    if (!$erro) {
      if ($_POST['form_acao'] === 'novo') {
        $stmt = $pdo->prepare("INSERT INTO familias (ordem_id,nome,descricao,exemplos,imagem,ativo) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$ordem_id, $nome, $descricao, $exemplos, $imagem, $ativo]);
        $msg = 'FamÃ­lia cadastrada!';
        $acao = 'listar';
      } elseif ($_POST['form_acao'] === 'editar') {
        $pid = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE familias SET ordem_id=?,nome=?,descricao=?,exemplos=?,imagem=?,ativo=? WHERE id=?");
        $stmt->execute([$ordem_id, $nome, $descricao, $exemplos, $imagem, $ativo, $pid]);
        $msg = 'FamÃ­lia atualizada!';
        $acao = 'listar';
      }
    }
  }

  if ($acao === 'deletar' && $id) {
    $pdo->prepare("DELETE FROM familias WHERE id=?")->execute([$id]);
    $msg = 'FamÃ­lia excluÃ­da.';
    $acao = 'listar';
  }

  $familia_edit = null;
  if ($acao === 'editar' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM familias WHERE id=?");
    $stmt->execute([$id]);
    $familia_edit = $stmt->fetch();
    if (!$familia_edit) $acao = 'listar';
  }
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
      <a href="familias.php" class="active">FamÃ­lias</a>
      <a href="chaves.php">Chaves DicotÃ´micas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="../index.php" target="_blank">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>FamÃ­lias</h1>
      <?php if ($acao === 'listar'): ?>
        <a href="?acao=novo<?= $filtro_ordem ? "&ordem_id=$filtro_ordem" : '' ?>" class="btn-primary">+ Nova FamÃ­lia</a>
      <?php else: ?>
        <a href="familias.php" class="btn-secondary">Voltar Ã  Lista</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <?php if ($acao === 'listar'):
        $where = $filtro_ordem ? "WHERE f.ordem_id=$filtro_ordem" : "";
        $familias = $pdo->query("SELECT f.*, o.nome AS ordem_nome FROM familias f JOIN ordens o ON o.id=f.ordem_id $where ORDER BY o.ordem_exibicao, f.nome")->fetchAll();
      ?>
        <div class="filter-bar">
          <span style="font-size:0.9rem;color:var(--texto-suave)">Filtrar por ordem:</span>
          <select onchange="location.href='familias.php?ordem_id='+this.value">
            <option value="0" <?= !$filtro_ordem ? 'selected' : '' ?>>Todas</option>
            <?php foreach ($ordens as $o): ?>
              <option value="<?= $o['id'] ?>" <?= $filtro_ordem == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
            <?php endforeach; ?>
          </select>
          <span style="font-size:0.88rem;color:var(--texto-suave)"><?= count($familias) ?> famÃ­lias</span>
        </div>
        <div class="card">
          <table>
            <thead>
              <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Ordem</th>
                <th>Exemplos</th>
                <th>AÃ§Ãµes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($familias as $f): ?>
                <tr>
                  <td><?= $f['imagem'] ? "<img src='../{$f['imagem']}' class='thumb'>" : "<div class='thumb-ph'>ImagemAqui</div>" ?></td>
                  <td><em><?= htmlspecialchars($f['nome']) ?></em></td>
                  <td><span class="tag-ordem"><?= htmlspecialchars($f['ordem_nome']) ?></span></td>
                  <td style="color:var(--texto-suave);font-size:0.88rem"><?= htmlspecialchars(mb_strimwidth($f['exemplos'] ?? '', 0, 50, 'â€¦')) ?></td>
                  <td>
                    <a href="?acao=editar&id=<?= $f['id'] ?>" class="btn-sm btn-edit">Editar</a>
                    <a href="?acao=deletar&id=<?= $f['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Excluir?')">Excluir</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php elseif ($acao === 'novo' || $acao === 'editar'):
        $e = $familia_edit ?: [];
        $preOrdem = $filtro_ordem ?: ($e['ordem_id'] ?? 0);
      ?>
        <div class="card">
          <div class="card-header">
            <h3><?= $acao === 'novo' ? 'Nova FamÃ­lia' : 'Editar: ' . htmlspecialchars($e['nome'] ?? '') ?></h3>
          </div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="form_acao" value="<?= $acao ?>">
              <?php if ($acao === 'editar'): ?>
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($e['imagem'] ?? '') ?>">
              <?php endif; ?>

              <div class="form-row">
                <div class="form-group">
                  <label class="lbl">Ordem</label>
                  <select name="ordem_id" class="form-control" required>
                    <option value="">Selecione.</option>
                    <?php foreach ($ordens as $o): ?>
                      <option value="<?= $o['id'] ?>" <?= $preOrdem == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="lbl">Nome da FamÃ­lia</label>
                  <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($e['nome'] ?? '') ?>" placeholder="Ex: Cicadidae">
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">DescriÃ§Ã£o</label>
                <textarea name="descricao" class="form-control"><?= htmlspecialchars($e['descricao'] ?? '') ?></textarea>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="lbl">Exemplos</label>
                  <input type="text" name="exemplos" class="form-control" value="<?= htmlspecialchars($e['exemplos'] ?? '') ?>" placeholder="Cigarras, cigarrinhas">
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:2px">
                  <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:0.92rem">
                    <input type="checkbox" name="ativo" <?= ($e['ativo'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:var(--verde)">
                    FamÃ­lia ativa
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Imagem</label>
                <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImg(this)">
                <p class="hint">JPG, PNG ou WebP - mÃ¡x 5MB<?= $e['imagem'] ?? '' ? '. Atual: <em>' . basename($e['imagem']) . '</em>' : '' ?></p>
                <?php if (!empty($e['imagem'])): ?>
                  <img src="../<?= htmlspecialchars($e['imagem']) ?>" class="img-preview" style="display:block;max-width:180px;border-radius:10px;margin-top:10px">
                <?php else: ?>
                  <img id="imgPreview" class="img-preview" style="display:none;max-width:180px;border-radius:10px;margin-top:10px">
                <?php endif; ?>
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar</button>
                <a href="familias.php" class="btn-secondary">Cancelar</a>
              </div>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script>
    function previewImg(input) {
      const prev = document.getElementById('imgPreview');
      if (!prev) return;
      if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
          prev.src = e.target.result;
          prev.style.display = 'block'
        };
        r.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>
