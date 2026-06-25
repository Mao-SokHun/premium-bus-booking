<?php
session_start();
include '../check_admin.php';
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$userId = (int) ($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? 'user';
$newPassword = $_POST['new_password'] ?? '';
$isSelf = $userId === (int) $_SESSION['user_id'];

if ($userId <= 0 || $username === '' || $email === '') {
    $_SESSION['msg'] = 'Please fill all required fields';
    header('Location: user_edit.php?id=' . $userId);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$userId]);
if (!$stmt->fetch()) {
    $_SESSION['alert'] = 'User not found';
    header('Location: users.php');
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
$stmt->execute([$username, $email, $userId]);
if ($stmt->fetch()) {
    $_SESSION['msg'] = 'Username or email already in use';
    header('Location: user_edit.php?id=' . $userId);
    exit;
}

if ($isSelf) {
    $role = 'admin';
}

if (!in_array($role, ['user', 'admin'], true)) {
    $role = 'user';
}

if ($newPassword !== '' && strlen($newPassword) < 6) {
    $_SESSION['msg'] = 'Password must be at least 6 characters';
    header('Location: user_edit.php?id=' . $userId);
    exit;
}

$sql = "UPDATE users SET username = ?, email = ?, role = ?";
$params = [$username, $email, $role];

if ($newPassword !== '') {
    $sql .= ", password = ?";
    $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
}

$sql .= " WHERE id = ?";
$params[] = $userId;

$stmt = $conn->prepare($sql);
$stmt->execute($params);

if ($isSelf) {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
}

$_SESSION['alert'] = 'User updated successfully';
header('Location: users.php');
exit;
