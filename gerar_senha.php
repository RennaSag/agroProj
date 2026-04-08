<?php
//arquivo de teste pra gerar senha hash
$hash = password_hash('admin123', PASSWORD_DEFAULT);
echo $hash;
?>

