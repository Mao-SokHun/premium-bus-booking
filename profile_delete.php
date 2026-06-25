<?php
session_start();
include 'check_login.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user || $user['role'] === 'admin') {
    $_SESSION['msg'] = 'Admin accounts cannot be deleted from here';
    header('Location: profile.php');
    exit;
}

$conn->prepare("DELETE FROM bookings WHERE user_id = ?")->execute([$userId]);
$conn->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

header('Location: index.php');
exit;
