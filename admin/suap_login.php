<?php
require_once '../includes/db.php';
require_once '../includes/suap.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$state = bin2hex(random_bytes(16));
$_SESSION['suap_oauth_state'] = $state;

header('Location: ' . suapAuthorizeUrl($state));
exit;
