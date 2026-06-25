<?php
session_start();
require_once dirname(__DIR__) . '/includes/oauth.php';

if (!oauth_provider_enabled('google')) {
    $_SESSION['msg'] = 'Google login is not configured yet.';
    header('Location: ' . get_app_base_url() . '/login.php');
    exit;
}

oauth_start_google();
