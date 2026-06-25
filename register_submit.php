<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$cfpassword = $_POST['cfpassword'];

if ($username == '' || $email == '' || $password == '') {
    $_SESSION['msg'] = 'Please fill all fields';
    header('Location: index.php');
    exit;
}

if ($password != $cfpassword) {
    $_SESSION['msg'] = 'Password not match';
    header('Location: index.php');
    exit;
}

$sql = "SELECT id FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    $_SESSION['msg'] = 'Username or email already used';
    header('Location: index.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?) RETURNING id";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $email, $hash]);
$userId = $stmt->fetchColumn();

$_SESSION['user_id'] = (int) $userId;
$_SESSION['username'] = $username;
$_SESSION['role'] = 'user';
header('Location: homepage.php');
exit;
