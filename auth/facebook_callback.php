<?php
session_start();
require_once dirname(__DIR__) . '/db.php';
require_once dirname(__DIR__) . '/includes/oauth.php';

try {
    if (isset($_GET['error'])) {
        throw new RuntimeException('Facebook sign-in was cancelled.');
    }
    oauth_handle_facebook_callback($conn);
} catch (Throwable $e) {
    $_SESSION['msg'] = $e->getMessage();
    header('Location: ' . get_app_base_url() . '/login.php');
    exit;
}
