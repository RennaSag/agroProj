<?php require_once '../includes/db.php'; 
requireAdmin(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Famílias</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/ui-base.css?v=20260527">
  <link rel="stylesheet" href="../assets/css/admin-familias.css?v=20260601">
  <link rel="stylesheet" href="../assets/css/admin-responsive.css?v=20260601">
</head>

<body>
  <?php
  $pdo = getDB();
  ensureFamiliaExemploImagensTable($pdo);
  $acao = $_GET['acao'] ?? 'listar';
  $id = (int)($_GET['id'] ?? 0);
  $filtro_ordem = (int)($_GET['ordem_id'] ?? 0);
  $msg = '';
  $erro = '';

  function salvarExemploImagensFamilia($familiaId, $files, $pdo, &$erro)
  {
    if (!isset($files['name']) || !is_array($files['name'])) return;

    foreach ($files['name'] as $index => $nomeOriginal) {
      $codigoErro = $files['error'][$index] ?? UPLOAD_ERR_NO_FILE;
      if ($codigoErro === UPLOAD_ERR_NO_FILE) continue;

      if ($codigoErro !== UPLOAD_ERR_OK) {
        $erro = 'Não foi possível enviar uma das imagens de exemplo.';
        return;
      }

      $arquivo = [
        'name' => $nomeOriginal,
        'type' => $files['type'][$index] ?? '',
        'tmp_name' => $files['tmp_name'][$index] ?? '',
        'error' => $codigoErro,
        'size' => $files['size'][$index] ?? 0,
      ];

      $upload = uploadImagem($arquivo, 'familia_exemplo');
      if (is_string($upload)) {
        adicionarFamiliaExemploImagem($familiaId, $upload, $pdo);
      } elseif (is_array($upload)) {
        $erro = $upload['error'];
        return;
      }
    }
  }

 
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
        $familiaId = (int)$pdo->lastInsertId();
        salvarExemploImagensFamilia($familiaId, $_FILES['exemplo_imagens'] ?? [], $pdo, $erro);
        if (!$erro) {
          $msg = 'Família cadastrada!';
          $acao = 'listar';
        } else {
          $id = $familiaId;
          $acao = 'editar';
        }
      } elseif ($_POST['form_acao'] === 'editar') {
        $pid = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE familias SET ordem_id=?,nome=?,descricao=?,exemplos=?,imagem=?,ativo=? WHERE id=?");
        $stmt->execute([$ordem_id, $nome, $descricao, $exemplos, $imagem, $ativo, $pid]);

        foreach ((array)($_POST['remover_exemplo_imagens'] ?? []) as $imagemId) {
          removerFamiliaExemploImagem((int)$imagemId, $pid, $pdo);
        }

        salvarExemploImagensFamilia($pid, $_FILES['exemplo_imagens'] ?? [], $pdo, $erro);
        if (!$erro) {
          $msg = 'Família atualizada!';
          $acao = 'listar';
        } else {
          $id = $pid;
          $acao = 'editar';
        }
      }
    }
  }

  if ($acao === 'deletar' && $id) {
    foreach (getFamiliaExemploImagens($id, $pdo) as $imagemExemplo) {
      removerArquivoUpload($imagemExemplo['imagem']);
    }
    $pdo->prepare("DELETE FROM familias WHERE id=?")->execute([$id]);
    $msg = 'Família excluída.';
    $acao = 'listar';
  }

  $familia_edit = null;
  $familia_exemplo_imagens = [];
  if ($acao === 'editar' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM familias WHERE id=?");
    $stmt->execute([$id]);
    $familia_edit = $stmt->fetch();
    if (!$familia_edit) {
      $acao = 'listar';
    } else {
      $familia_exemplo_imagens = getFamiliaExemploImagens($id, $pdo);
    }
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
      <a href="familias.php" class="active">Famílias</a>
      <a href="chaves.php">Chaves Dicotômicas</a>
      <div class="nav-section">Sistema</div>
      <a href="admins.php">Administradores</a>
      <a href="configuracoes.php">Configurações</a>
      <a href="../index.php" target="_blank" rel="noopener">Ver Site</a>
    </div>
    <div class="sidebar-bottom"><a href="logout.php">Sair</a></div>
  </nav>

  <div class="main">
    <div class="topbar">
      <h1>Famílias</h1>
      <?php if ($acao === 'listar'): ?>
        <a href="?acao=novo<?= $filtro_ordem ? "&ordem_id=$filtro_ordem" : '' ?>" class="btn-primary">+ Nova Família</a>
      <?php else: ?>
        <a href="familias.php" class="btn-secondary">Voltar à Lista</a>
      <?php endif; ?>
    </div>
    <div class="content">
      <?php if ($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
      <?php if ($erro): ?><div class="alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <?php if ($acao === 'listar'):
        $where = $filtro_ordem ? "WHERE f.ordem_id=$filtro_ordem" : "";
        $familias = $pdo->query("
          SELECT f.*, o.nome AS ordem_nome,
                 (SELECT COUNT(*) FROM familia_exemplo_imagens fei WHERE fei.familia_id = f.id) AS exemplo_imagens_total
          FROM familias f
          JOIN ordens o ON o.id=f.ordem_id
          $where
          ORDER BY o.ordem_exibicao, f.nome
        ")->fetchAll();
      ?>
        <div class="filter-bar">
          <span style="font-size:0.9rem;color:var(--texto-suave)">Filtrar por ordem:</span>
          <select onchange="location.href='familias.php?ordem_id='+this.value">
            <option value="0" <?= !$filtro_ordem ? 'selected' : '' ?>>Todas</option>
            <?php foreach ($ordens as $o): ?>
              <option value="<?= $o['id'] ?>" <?= $filtro_ordem == $o['id'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
            <?php endforeach; ?>
          </select>
          <span style="font-size:0.88rem;color:var(--texto-suave)"><?= count($familias) ?> famílias</span>
        </div>
        <div class="card">
          <table>
            <thead>
              <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Ordem</th>
                <th>Exemplos</th>
                <th>Imagens de exemplos</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($familias as $f): ?>
                <tr>
                  <td>
                    <?php if (!empty($f['imagem'])): ?>
                      <img src="../<?= htmlspecialchars($f['imagem']) ?>" class="thumb" alt="">
                    <?php else: ?>
                      <div class="missing-thumb" aria-label="Sem imagem"><span aria-hidden="true">▧</span>Sem imagem</div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <em><?= htmlspecialchars($f['nome']) ?></em>
                    <?php if (empty($f['imagem'])): ?><span class="content-badge">Sem imagem</span><?php endif; ?>
                  </td>
                  <td><span class="tag-ordem"><?= htmlspecialchars($f['ordem_nome']) ?></span></td>
                  <td style="color:var(--texto-suave);font-size:0.88rem"><?= htmlspecialchars(mb_strimwidth($f['exemplos'] ?? '', 0, 50, '…')) ?></td>
                  <td>
                    <span class="example-count-badge"><?= (int)$f['exemplo_imagens_total'] ?> imagem(ns)</span>
                  </td>
                  <td><div class="admin-table-actions">
                    <a href="?acao=editar&id=<?= $f['id'] ?>" class="btn-sm btn-edit">Editar</a>
                    <a href="?acao=deletar&id=<?= $f['id'] ?>" class="btn-sm btn-del" onclick="return confirm('Excluir?')">Excluir</a>
                  </div></td>
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
            <h3><?= $acao === 'novo' ? 'Nova Família' : 'Editar: ' . htmlspecialchars($e['nome'] ?? '') ?></h3>
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
                  <label class="lbl">Nome da Família</label>
                  <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($e['nome'] ?? '') ?>" placeholder="Ex: Cicadidae">
                </div>
              </div>

              <div class="form-group">
                <label class="lbl">Descrição</label>
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
                    Família ativa
                  </label>
                </div>
              </div>

              <div class="form-group example-images-field">
                <label class="lbl">Imagens de exemplos</label>
                <input type="file" id="exemploImagens" name="exemplo_imagens[]" class="form-control" accept="image/*" multiple>
                <p class="hint">Envie uma ou mais imagens para ilustrar os exemplos da família. JPG, PNG ou WebP - máx 5MB por arquivo.</p>
                <div id="exampleImagesPreview" class="example-images-preview" hidden></div>

                <?php if ($acao === 'editar'): ?>
                  <?php if ($familia_exemplo_imagens): ?>
                    <div class="example-images-current" aria-label="Imagens de exemplos cadastradas">
                      <?php foreach ($familia_exemplo_imagens as $imagemExemplo): ?>
                        <label class="example-image-admin-card">
                          <img src="../<?= htmlspecialchars($imagemExemplo['imagem']) ?>" alt="Imagem de exemplo cadastrada">
                          <span>
                            <input type="checkbox" name="remover_exemplo_imagens[]" value="<?= (int)$imagemExemplo['id'] ?>">
                            Remover
                          </span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div class="upload-empty">Nenhuma imagem de exemplo cadastrada para esta família.</div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>

              <div class="form-group">
                <label class="lbl">Imagem</label>
                <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImg(this)">
                <p class="hint">JPG, PNG ou WebP - máx 5MB<?= $e['imagem'] ?? '' ? '. Atual: <em>' . basename($e['imagem']) . '</em>' : '' ?></p>
                <?php if (!empty($e['imagem'])): ?>
                  <img src="../<?= htmlspecialchars($e['imagem']) ?>" class="img-preview" style="display:block;max-width:180px;border-radius:10px;margin-top:10px" alt="Pré-visualização da imagem atual">
                <?php else: ?>
                  <div id="imgPreviewEmpty" class="upload-empty">Sem imagem cadastrada. Selecione um arquivo para visualizar antes de salvar.</div>
                  <img id="imgPreview" class="img-preview" hidden style="display:none;max-width:180px;border-radius:10px;margin-top:10px" alt="Pré-visualização da nova imagem">
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
          prev.hidden = false;
          prev.style.display = 'block';
          const empty = document.getElementById('imgPreviewEmpty');
          if (empty) empty.hidden = true;
        };
        r.readAsDataURL(input.files[0]);
      }
    }

    const exemploInput = document.getElementById('exemploImagens');
    const exemploPreview = document.getElementById('exampleImagesPreview');
    if (exemploInput && exemploPreview) {
      exemploInput.addEventListener('change', () => {
        exemploPreview.innerHTML = '';
        const files = Array.from(exemploInput.files || []).filter(file => file.type.startsWith('image/'));

        if (!files.length) {
          exemploPreview.hidden = true;
          return;
        }

        files.forEach(file => {
          const item = document.createElement('div');
          item.className = 'example-image-preview-item';

          const img = document.createElement('img');
          img.alt = `Pré-visualização de ${file.name}`;

          const caption = document.createElement('span');
          caption.textContent = file.name;

          const reader = new FileReader();
          reader.addEventListener('load', event => {
            img.src = event.target.result;
          });
          reader.readAsDataURL(file);

          item.append(img, caption);
          exemploPreview.appendChild(item);
        });

        exemploPreview.hidden = false;
      });
    }
  </script>
  <script src="../assets/js/admin-layout.js?v=20260527"></script>
</body>

</html>
