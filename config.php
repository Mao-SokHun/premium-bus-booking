<?php
// Database settings (local defaults — overridden by DATABASE_URL on Render)
$host = 'localhost';
$port = '5432';
$dbname = 'car_booking';
$user = 'postgres';
$pass = '4944';
$db_sslmode = '';

// Render / production: DATABASE_URL from PostgreSQL service
if ($databaseUrl = getenv('DATABASE_URL')) {
    $parts = parse_url($databaseUrl);
    if ($parts !== false) {
        $host = $parts['host'] ?? $host;
        $port = (string) ($parts['port'] ?? '5432');
        $dbname = ltrim($parts['path'] ?? '', '/') ?: $dbname;
        $user = $parts['user'] ?? $user;
        $pass = $parts['pass'] ?? $pass;
        $db_sslmode = 'require';
    }
}

// Application base URL (required for OAuth redirect URIs)
// On Render, leave empty — auto-detected. Or set APP_BASE_URL env var.
$app_base_url = getenv('APP_BASE_URL') ?: '';

// ---------------------------------------------------------------------------
// Google OAuth
// ---------------------------------------------------------------------------
$google_enabled = filter_var(getenv('GOOGLE_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN);
$google_client_id = getenv('GOOGLE_CLIENT_ID') ?: '';
$google_client_secret = getenv('GOOGLE_CLIENT_SECRET') ?: '';

// ---------------------------------------------------------------------------
// Facebook OAuth
// ---------------------------------------------------------------------------
$facebook_enabled = filter_var(getenv('FACEBOOK_ENABLED') ?: 'false', FILTER_VALIDATE_BOOLEAN);
$facebook_app_id = getenv('FACEBOOK_APP_ID') ?: '';
$facebook_app_secret = getenv('FACEBOOK_APP_SECRET') ?: '';

// Local dev: enable OAuth in config when env vars are not set
if (!getenv('DATABASE_URL') && !getenv('PHP_ENV')) {
    $google_enabled = false;
    $facebook_enabled = false;
}
