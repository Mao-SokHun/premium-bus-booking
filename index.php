<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
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
    <title>Create Account - Premium Bus</title>
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
            <h1>Start your journey with us</h1>
            <p>Create a free account to book luxury vans, premium coaches, and air buses anywhere in Cambodia.</p>
        </div>
        <div class="auth-side-features">
            <div class="auth-side-feat"><i class="fa-solid fa-bolt"></i> Book in under 2 minutes</div>
            <div class="auth-side-feat"><i class="fa-solid fa-shield-halved"></i> Fully insured fleet</div>
            <div class="auth-side-feat"><i class="fa-solid fa-headset"></i> 24/7 support team</div>
        </div>
    </aside>

    <main class="auth-main">
        <div class="auth-form-box">
            <div class="auth-mobile-brand">
                <div class="auth-side-icon"><i class="fa-solid fa-bus"></i></div>
                <span>Premium Bus</span>
            </div>

            <h2>Create account</h2>
            <p class="subtitle">Fill in your details to get started</p>

            <?php if ($msg != '') {
                $cls = $msgType === 'success' ? 'success-msg' : 'error-msg';
                echo '<div class="' . $cls . '"><i class="fa-solid fa-' . ($msgType === 'success' ? 'circle-check' : 'circle-exclamation') . '"></i> ' . htmlspecialchars($msg) . '</div>';
            } ?>
            <?php include 'includes/social_login.php'; ?>

            <form action="register_submit.php" method="post" autocomplete="on">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" autocomplete="username" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@email.com" autocomplete="email" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 6 characters" autocomplete="new-password" required>
                </div>
                <div class="field">
                    <label for="cfpassword">Confirm password</label>
                    <input type="password" id="cfpassword" name="cfpassword" placeholder="Repeat your password" autocomplete="new-password" required>
                </div>
                <button type="submit" class="btn-auth">
                    <i class="fa-solid fa-user-plus"></i> Create account
                </button>
            </form>

            <div class="auth-footer-link">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>
    </main>
</div>
</body>
</html>
