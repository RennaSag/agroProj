<?php
require_once '../includes/db.php';
require_once '../includes/suap.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$expectedState = $_SESSION['suap_oauth_state'] ?? null;
unset($_SESSION['suap_oauth_state']);

if (isset($_GET['error'])) {
    header('Location: login.php?erro=suap_negado');
    exit;
}

$code = $_GET['code'] ?? '';
$state = $_GET['state'] ?? '';

if (!$code || !$expectedState || !hash_equals($expectedState, $state)) {
    header('Location: login.php?erro=suap_invalido');
    exit;
}

$accessToken = suapExchangeCodeForToken($code);
if (!$accessToken) {
    header('Location: login.php?erro=suap_token');
    exit;
}

$perfil = suapGetUserInfo($accessToken);
$email = trim($perfil['email'] ?? '');

if (!$email) {
    header('Location: login.php?erro=suap_perfil');
    exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: login.php?erro=suap_nao_autorizado');
    exit;
}

$nomeSuap = trim($perfil['nome'] ?? '') ?: $admin['nome'];
if ($nomeSuap !== $admin['nome']) {
    $pdo->prepare("UPDATE admins SET nome = ? WHERE id = ?")->execute([$nomeSuap, $admin['id']]);
}

$_SESSION['admin_id']   = $admin['id'];
$_SESSION['admin_nome'] = $nomeSuap;
header('Location: index.php');
exit;
