<?php
// api.php - endpoint público
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
require_once 'includes/db.php';

$action = $_GET['action'] ?? '';
$pdo = getDB();

switch ($action) {

    case 'ordens':
        $stmt = $pdo->query("
            SELECT o.id, o.nome, o.imagem,
                   (SELECT COUNT(*) FROM familias f WHERE f.ordem_id = o.id AND f.ativo=TRUE) AS total_familias
            FROM ordens o
            WHERE o.ativo=TRUE
            ORDER BY o.ordem_exibicao, o.id
        ");
        echo json_encode($stmt->fetchAll());
        break;

    case 'ordem':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM ordens WHERE id=? AND ativo=TRUE");
        $stmt->execute([$id]);
        $ordem = $stmt->fetch();
        if (!$ordem) {
            echo json_encode(['error' => 'Não encontrada']);
            break;
        }

        // Busca famílias da ordem
        $sf = $pdo->prepare("SELECT nome FROM familias WHERE ordem_id=? AND ativo=TRUE ORDER BY nome");
        $sf->execute([$id]);
        $ordem['familias'] = array_column($sf->fetchAll(), 'nome');

        echo json_encode($ordem);
        break;

    case 'passos':
        $ordemId = (int)($_GET['ordem_id'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT cp.*, 
                   fs.nome AS sim_familia, fs.descricao AS sim_desc, fs.exemplos AS sim_ex, fs.imagem AS sim_img,
                   fn.nome AS nao_familia, fn.descricao AS nao_desc, fn.exemplos AS nao_ex, fn.imagem AS nao_img
            FROM chave_passos cp
            LEFT JOIN familias fs ON fs.id = cp.sim_resultado_familia_id
            LEFT JOIN familias fn ON fn.id = cp.nao_resultado_familia_id
            WHERE cp.ordem_id = ?
            ORDER BY cp.passo_numero
        ");
        $stmt->execute([$ordemId]);
        $passos = $stmt->fetchAll();

        $familiaIds = [];
        foreach ($passos as $p) {
            if (!empty($p['sim_resultado_familia_id'])) {
                $familiaIds[] = (int)$p['sim_resultado_familia_id'];
            }
            if (!empty($p['nao_resultado_familia_id'])) {
                $familiaIds[] = (int)$p['nao_resultado_familia_id'];
            }
        }
        $exemploImagens = getFamiliaExemploImagensMap($familiaIds, $pdo);

        $total = count($passos);
        foreach ($passos as &$p) {
            $p['total_passos'] = $total;
            $simFamiliaId = (int)($p['sim_resultado_familia_id'] ?? 0);
            $naoFamiliaId = (int)($p['nao_resultado_familia_id'] ?? 0);
            $p['sim_exemplo_imagens'] = $simFamiliaId ? ($exemploImagens[$simFamiliaId] ?? []) : [];
            $p['nao_exemplo_imagens'] = $naoFamiliaId ? ($exemploImagens[$naoFamiliaId] ?? []) : [];
        }
        echo json_encode($passos);
        break;

    case 'familia':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT f.*, o.nome AS ordem_nome FROM familias f JOIN ordens o ON o.id=f.ordem_id WHERE f.id=?");
        $stmt->execute([$id]);
        $familia = $stmt->fetch();
        if ($familia) {
            $familia['exemplo_imagens'] = getFamiliaExemploImagens($id, $pdo);
        }
        echo json_encode($familia);
        break;

    case 'configuracoes_chave':
        echo json_encode([
            'exibir_miniaturas_historico' => configuracaoAtiva('exibir_miniaturas_historico', true),
        ]);
        break;

    case 'buscar':
        $termo = trim($_GET['q'] ?? '');
        if (mb_strlen($termo) < 2) {
            echo json_encode(['ordens' => [], 'familias' => []]);
            break;
        }
        $like = '%' . $termo . '%';

        $so = $pdo->prepare("SELECT id, nome, imagem FROM ordens WHERE ativo=TRUE AND nome ILIKE ? ORDER BY nome LIMIT 8");
        $so->execute([$like]);

        $sf = $pdo->prepare("
            SELECT f.id, f.nome, f.imagem, f.ordem_id, o.nome AS ordem_nome
            FROM familias f
            JOIN ordens o ON o.id = f.ordem_id
            WHERE f.ativo=TRUE AND o.ativo=TRUE AND f.nome ILIKE ?
            ORDER BY f.nome LIMIT 8
        ");
        $sf->execute([$like]);

        echo json_encode([
            'ordens' => $so->fetchAll(),
            'familias' => $sf->fetchAll(),
        ]);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Ação não encontrada']);
}
