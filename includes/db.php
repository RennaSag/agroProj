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
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
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

function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /agroProj/admin/login.php');
        exit;
    }
}
//requerimento de adm pras paginas
