<?php
//arquivo pra testar se a senha hash ta igual no db
require_once 'includes/db.php';
$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute(['']);
$admin = $stmt->fetch();

echo "Achou: " . ($admin ? 'SIM' : 'NÃO') . "<br>";
echo "Hash no banco: " . $admin['senha'] . "<br>";
echo "Senha bate: " . (password_verify('admin123', $admin['senha']) ? 'SIM' : 'NÃO') . "<br>";
?>
