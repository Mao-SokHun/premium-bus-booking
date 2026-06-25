<?php
include '../check_admin.php';
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$userId = (int)$_POST['user_id'];
$action = $_POST['action'] ?? '';

if ($userId === (int)$_SESSION['user_id']) {
    $_SESSION['alert'] = 'Cannot modify your own account';
    header('Location: users.php');
    exit;
}

if ($action === 'promote') {
    $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?")->execute([$userId]);
    $_SESSION['alert'] = 'User promoted to admin';
} elseif ($action === 'demote') {
    $conn->prepare("UPDATE users SET role = 'user' WHERE id = ?")->execute([$userId]);
    $_SESSION['alert'] = 'Admin role removed';
} elseif ($action === 'delete') {
    $conn->prepare("DELETE FROM bookings WHERE user_id = ?")->execute([$userId]);
    $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
    $_SESSION['alert'] = 'User deleted';
}

header('Location: users.php');
exit;
