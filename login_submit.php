<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$login = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
    $_SESSION['msg'] = 'Please fill all fields';
    header('Location: login.php');
    exit;
}

$sql = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$login, $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (empty($user['password'])) {
        $provider = $user['oauth_provider'] ?? 'social';
        $_SESSION['msg'] = 'This account uses ' . ucfirst($provider) . ' sign-in. Please use the button above.';
        header('Location: login.php');
        exit;
    }

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? 'user';

        if ($_SESSION['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: homepage.php');
        }
        exit;
    }
}

$_SESSION['msg'] = 'Wrong username or password';
header('Location: login.php');
exit;
