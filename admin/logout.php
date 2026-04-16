<?php
require_once '../includes/db.php';
requireAdmin();
session_destroy();
header('Location: login.php');
exit;
