<?php
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $_SESSION = [];
    session_destroy();
}

header('Location: ' . BASE_URL . '/admin/login.php');
exit;