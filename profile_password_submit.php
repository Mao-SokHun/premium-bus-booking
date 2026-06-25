<?php
session_start();
include 'check_login.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile_password.php');
    exit;
}

$userId = $_SESSION['user_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($new === '' || $confirm === '') {
    $_SESSION['msg'] = 'Please fill all fields';
    header('Location: profile_password.php');
    exit;
}

if (strlen($new) < 6) {
    $_SESSION['msg'] = 'Password must be at least 6 characters';
    header('Location: profile_password.php');
    exit;
}

if ($new !== $confirm) {
    $_SESSION['msg'] = 'New passwords do not match';
    header('Location: profile_password.php');
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!empty($user['password'])) {
    if (!password_verify($current, $user['password'])) {
        $_SESSION['msg'] = 'Current password is incorrect';
        header('Location: profile_password.php');
        exit;
    }
}

$hash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$hash, $userId]);

$_SESSION['msg'] = 'Password updated successfully';
header('Location: profile.php');
exit;
