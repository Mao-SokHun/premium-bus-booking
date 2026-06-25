<?php
session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signed Out - Premium Bus</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="auth-page">
<div class="auth-shell">
    <aside class="auth-side">
        <div class="auth-side-brand">
            <div class="auth-side-icon"><i class="fa-solid fa-bus"></i></div>
            <span>Premium Bus</span>
        </div>
        <div class="auth-side-content">
            <h1>See you again soon</h1>
            <p>You've been signed out safely. We hope to drive you again on your next adventure.</p>
        </div>
    </aside>
    <main class="auth-main">
        <div class="auth-form-box" style="text-align:center;">
            <div style="font-size:48px;color:#3b82f6;margin-bottom:20px;"><i class="fa-solid fa-circle-check"></i></div>
            <h2>Signed out</h2>
            <p class="subtitle">Thank you for using Premium Bus</p>
            <a href="login.php" class="btn-auth" style="text-decoration:none;margin-top:16px;">
                <i class="fa-solid fa-arrow-right"></i> Sign in again
            </a>
        </div>
    </main>
</div>
</body>
</html>
