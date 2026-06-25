<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin/dashboard.php' : 'homepage.php'));
    exit;
}
$msg = '';
$msgType = 'error';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgType = $_SESSION['msg_type'] ?? 'error';
    unset($_SESSION['msg'], $_SESSION['msg_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Premium Bus</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="image/logo 2.jpg">
</head>
<body class="auth-page">
<div class="auth-shell">
    <aside class="auth-side">
        <div class="auth-side-brand">
            <div class="auth-side-icon"><i class="fa-solid fa-bus"></i></div>
            <span>Premium Bus</span>
        </div>
        <div class="auth-side-content">
            <h1>Welcome back</h1>
            <p>Sign in to manage your bookings, track reservations, and reserve your next ride across Cambodia.</p>
        </div>
        <div class="auth-side-features">
            <div class="auth-side-feat"><i class="fa-solid fa-calendar-check"></i> Manage all bookings</div>
            <div class="auth-side-feat"><i class="fa-solid fa-bus"></i> 3 vehicle categories</div>
            <div class="auth-side-feat"><i class="fa-solid fa-star"></i> Trusted by thousands</div>
        </div>
    </aside>

    <main class="auth-main">
        <div class="auth-form-box">
            <div class="auth-mobile-brand">
                <div class="auth-side-icon"><i class="fa-solid fa-bus"></i></div>
                <span>Premium Bus</span>
            </div>

            <h2>Sign in</h2>
            <p class="subtitle">Enter your credentials to continue</p>

            <?php if ($msg != '') {
                $cls = $msgType === 'success' ? 'success-msg' : 'error-msg';
                echo '<div class="' . $cls . '"><i class="fa-solid fa-' . ($msgType === 'success' ? 'circle-check' : 'circle-exclamation') . '"></i> ' . htmlspecialchars($msg) . '</div>';
            } ?>
            <?php include 'includes/social_login.php'; ?>

            <form action="login_submit.php" method="post" autocomplete="on">
                <div class="field">
                    <label for="username">Username or email</label>
                    <input type="text" id="username" name="username" placeholder="Username or email address" autocomplete="username" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Your password" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn-auth">
                    <i class="fa-solid fa-arrow-right"></i> Sign in
                </button>
            </form>

            <div class="auth-footer-link">
                Don't have an account? <a href="index.php">Create one</a>
            </div>
            <div class="admin-hint">Demo account: <strong>admin</strong> / <strong>admin123</strong></div>
        </div>
    </main>
</div>
</body>
</html>
