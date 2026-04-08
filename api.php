<?php
// api.php - endpoint público
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
require_once 'includes/db.php';

$action = $_GET['action'] ?? '';
$pdo = getDB();

switch ($action) {

    case 'ordens':
        $stmt = $pdo->query("SELECT id, nome, imagem FROM ordens WHERE ativo=1 ORDER BY ordem_exibicao, id");
        echo json_encode($stmt->fetchAll());
        break;

    case 'ordem':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM ordens WHERE id=? AND ativo=1");
        $stmt->execute([$id]);
        $ordem = $stmt->fetch();
        if (!$ordem) {
            echo json_encode(['error' => 'Não encontrada']);
            break;
        }

        // Busca famílias da ordem
        $sf = $pdo->prepare("SELECT nome FROM familias WHERE ordem_id=? AND ativo=1 ORDER BY nome");
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

        
        $total = count($passos);
        foreach ($passos as &$p) {
            $p['total_passos'] = $total;
        }
        echo json_encode($passos);
        break;

    case 'familia':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT f.*, o.nome AS ordem_nome FROM familias f JOIN ordens o ON o.id=f.ordem_id WHERE f.id=?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Ação não encontrada']);
}
