<?php
// includes/db.php

require_once __DIR__ . '/config.php';

session_start();

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'entomologia'));

function getDB()
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'pgsql:host=' . DB_HOST . ';port=5432;dbname=' . DB_NAME . ';sslmode=require',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Erro de conexão: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

define('UPLOAD_DIR', __DIR__ . '/../uploads/insetos/');
define('UPLOAD_URL', 'uploads/insetos/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

function uploadImagem($file, $prefixo = 'inseto')
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) return null;
    if ($file['size'] > MAX_FILE_SIZE) return ['error' => 'Arquivo muito grande (máx 5MB)'];
    if (!in_array($file['type'], ALLOWED_TYPES)) return ['error' => 'Tipo não permitido. Use JPG, PNG ou WebP'];

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nome = $prefixo . '_' . uniqid() . '.' . strtolower($ext);
    $destino = UPLOAD_DIR . $nome;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    if (move_uploaded_file($file['tmp_name'], $destino)) {
        return UPLOAD_URL . $nome;
    }
    return ['error' => 'Falha ao salvar arquivo'];
}

function isAdmin()
{
    return isset($_SESSION['admin_id']);
}

function requireAdmin()
{
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}
//requerimento de adm pras paginas

function ensureConfiguracoesTable($pdo = null)
{
    $pdo = $pdo ?: getDB();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS configuracoes (
            chave varchar(100) NOT NULL,
            valor varchar(255) NOT NULL,
            descricao varchar(255) DEFAULT NULL,
            atualizado_em timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (chave)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $stmt = $pdo->prepare("
        INSERT IGNORE INTO configuracoes (chave, valor, descricao)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        'exibir_miniaturas_historico',
        '1',
        'Exibir miniaturas das opções no histórico da identificação'
    ]);
}

function getConfiguracao($chave, $padrao = '')
{
    $pdo = getDB();
    ensureConfiguracoesTable($pdo);

    $stmt = $pdo->prepare("SELECT valor FROM configuracoes WHERE chave = ?");
    $stmt->execute([$chave]);
    $valor = $stmt->fetchColumn();

    return $valor === false ? $padrao : $valor;
}

function setConfiguracao($chave, $valor, $descricao = null)
{
    $pdo = getDB();
    ensureConfiguracoesTable($pdo);

    $stmt = $pdo->prepare("
        INSERT INTO configuracoes (chave, valor, descricao)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
            valor = VALUES(valor),
            descricao = VALUES(descricao)
    ");
    $stmt->execute([$chave, $valor, $descricao]);
}

function configuracaoAtiva($chave, $padrao = true)
{
    $valor = getConfiguracao($chave, $padrao ? '1' : '0');
    return in_array(strtolower((string)$valor), ['1', 'true', 'sim', 'yes', 'on'], true);
}

function ensureFamiliaExemploImagensTable($pdo = null)
{
    $pdo = $pdo ?: getDB();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS familia_exemplo_imagens (
            id int(11) NOT NULL AUTO_INCREMENT,
            familia_id int(11) NOT NULL,
            imagem varchar(255) NOT NULL,
            ordem int(11) NOT NULL DEFAULT 0,
            criado_em timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (id),
            KEY familia_id (familia_id),
            CONSTRAINT familia_exemplo_imagens_ibfk_1
                FOREIGN KEY (familia_id) REFERENCES familias (id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

function getFamiliaExemploImagens($familiaId, $pdo = null)
{
    $pdo = $pdo ?: getDB();
    ensureFamiliaExemploImagensTable($pdo);

    $stmt = $pdo->prepare("
        SELECT id, familia_id, imagem, ordem
        FROM familia_exemplo_imagens
        WHERE familia_id = ?
        ORDER BY ordem, id
    ");
    $stmt->execute([(int)$familiaId]);
    return $stmt->fetchAll();
}

function getFamiliaExemploImagensMap(array $familiaIds, $pdo = null)
{
    $ids = array_values(array_unique(array_filter(array_map('intval', $familiaIds))));
    if (!$ids) return [];

    $pdo = $pdo ?: getDB();
    ensureFamiliaExemploImagensTable($pdo);

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("
        SELECT id, familia_id, imagem, ordem
        FROM familia_exemplo_imagens
        WHERE familia_id IN ($placeholders)
        ORDER BY ordem, id
    ");
    $stmt->execute($ids);

    $map = [];
    foreach ($ids as $id) {
        $map[$id] = [];
    }

    foreach ($stmt->fetchAll() as $row) {
        $familiaId = (int)$row['familia_id'];
        $map[$familiaId][] = $row;
    }

    return $map;
}

function adicionarFamiliaExemploImagem($familiaId, $imagem, $pdo = null)
{
    $pdo = $pdo ?: getDB();
    ensureFamiliaExemploImagensTable($pdo);

    $ordemStmt = $pdo->prepare("SELECT COALESCE(MAX(ordem), 0) + 1 FROM familia_exemplo_imagens WHERE familia_id = ?");
    $ordemStmt->execute([(int)$familiaId]);
    $ordem = (int)$ordemStmt->fetchColumn();

    $stmt = $pdo->prepare("
        INSERT INTO familia_exemplo_imagens (familia_id, imagem, ordem)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([(int)$familiaId, $imagem, $ordem]);
}

function removerArquivoUpload($caminho)
{
    $caminho = ltrim((string)$caminho, '/');
    if ($caminho === '' || strpos($caminho, UPLOAD_URL) !== 0) {
        return false;
    }

    $arquivo = UPLOAD_DIR . basename($caminho);
    $base = realpath(UPLOAD_DIR);
    $real = realpath($arquivo);

    if (!$base || !$real || strpos($real, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($real)) {
        return false;
    }

    return @unlink($real);
}

function removerFamiliaExemploImagem($imagemId, $familiaId, $pdo = null, $removerArquivo = true)
{
    $pdo = $pdo ?: getDB();
    ensureFamiliaExemploImagensTable($pdo);

    $stmt = $pdo->prepare("SELECT imagem FROM familia_exemplo_imagens WHERE id = ? AND familia_id = ?");
    $stmt->execute([(int)$imagemId, (int)$familiaId]);
    $imagem = $stmt->fetchColumn();
    if ($imagem === false) return false;

    $delete = $pdo->prepare("DELETE FROM familia_exemplo_imagens WHERE id = ? AND familia_id = ?");
    $delete->execute([(int)$imagemId, (int)$familiaId]);

    if ($removerArquivo) {
        removerArquivoUpload($imagem);
    }

    return true;
}
