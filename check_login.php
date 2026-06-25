<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = 'Please login first';
    header('Location: login.php');
    exit;
}

// ensure role is loaded for existing sessions
if (!isset($_SESSION['role'])) {
    include_once 'db.php';
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $_SESSION['role'] = $user['role'] ?? 'user';
}
