<?php
session_start();
include 'check_login.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile_edit.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$userId = $_SESSION['user_id'];

if ($username === '' || $email === '') {
    $_SESSION['msg'] = 'Please fill all fields';
    header('Location: profile_edit.php');
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
$stmt->execute([$username, $email, $userId]);
if ($stmt->fetch()) {
    $_SESSION['msg'] = 'Username or email already in use';
    header('Location: profile_edit.php');
    exit;
}

$stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
$stmt->execute([$username, $email, $userId]);

$_SESSION['username'] = $username;
$_SESSION['msg'] = 'Profile updated successfully';
header('Location: profile.php');
exit;
