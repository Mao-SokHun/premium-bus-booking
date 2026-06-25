<?php

require_once dirname(__DIR__) . '/config.php';

function oauth_project_root() {
    return dirname(__DIR__);
}

function get_app_base_url() {
    global $app_base_url;

    if (!empty($app_base_url)) {
        return rtrim($app_base_url, '/');
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $projectPath = str_replace('\\', '/', oauth_project_root());
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');

    $relativePath = '';
    if ($docRoot !== '' && strpos($projectPath, $docRoot) === 0) {
        $relativePath = substr($projectPath, strlen($docRoot));
    }

    return $scheme . '://' . $host . $relativePath;
}

function oauth_redirect_uri($provider) {
    return get_app_base_url() . '/auth/' . $provider . '_callback.php';
}

function oauth_provider_enabled($provider) {
    global $google_enabled, $google_client_id, $google_client_secret;
    global $facebook_enabled, $facebook_app_id, $facebook_app_secret;

    if ($provider === 'google') {
        return !empty($google_enabled)
            && $google_client_id !== ''
            && $google_client_secret !== '';
    }

    if ($provider === 'facebook') {
        return !empty($facebook_enabled)
            && $facebook_app_id !== ''
            && $facebook_app_secret !== '';
    }

    return false;
}

function oauth_any_enabled() {
    return oauth_provider_enabled('google') || oauth_provider_enabled('facebook');
}

function oauth_http_request($method, $url, $params = [], $headers = []) {
    $ch = curl_init();

    if (strtoupper($method) === 'GET' && !empty($params)) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
    }

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    if (strtoupper($method) === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        throw new RuntimeException('OAuth request failed: ' . $error);
    }

    $data = json_decode($response, true);
    if ($status >= 400) {
        $message = is_array($data)
            ? ($data['error']['message'] ?? $data['error_description'] ?? $data['error'] ?? 'Unknown OAuth error')
            : 'HTTP ' . $status;
        throw new RuntimeException((string) $message);
    }

    return is_array($data) ? $data : [];
}

function oauth_set_state($provider) {
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;
    $_SESSION['oauth_provider'] = $provider;
    return $state;
}

function oauth_verify_state($provider, $state) {
    $valid = isset($_SESSION['oauth_state'], $_SESSION['oauth_provider'])
        && hash_equals($_SESSION['oauth_state'], $state)
        && $_SESSION['oauth_provider'] === $provider;

    unset($_SESSION['oauth_state'], $_SESSION['oauth_provider']);
    return $valid;
}

function oauth_unique_username($conn, $baseName) {
    $base = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($baseName));
    if ($base === '') {
        $base = 'user';
    }
    if (strlen($base) > 80) {
        $base = substr($base, 0, 80);
    }

    $username = $base;
    $suffix = 1;

    while (true) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if (!$stmt->fetch()) {
            return $username;
        }
        $username = $base . $suffix;
        $suffix++;
    }
}

function oauth_login_user($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'] ?? 'user';

    if (($_SESSION['role'] ?? 'user') === 'admin') {
        header('Location: ' . get_app_base_url() . '/admin/dashboard.php');
    } else {
        header('Location: ' . get_app_base_url() . '/homepage.php');
    }
    exit;
}

function oauth_find_or_create_user($conn, $provider, $oauthId, $email, $displayName) {
    $stmt = $conn->prepare('SELECT * FROM users WHERE oauth_provider = ? AND oauth_id = ?');
    $stmt->execute([$provider, $oauthId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        return $user;
    }

    if ($email !== '') {
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $conn->prepare('UPDATE users SET oauth_provider = ?, oauth_id = ? WHERE id = ?');
            $stmt->execute([$provider, $oauthId, $user['id']]);
            $user['oauth_provider'] = $provider;
            $user['oauth_id'] = $oauthId;
            return $user;
        }
    }

    $usernameBase = $email !== '' ? strstr($email, '@', true) : $displayName;
    $username = oauth_unique_username($conn, $usernameBase);
    $emailValue = $email !== '' ? $email : $username . '@' . $provider . '.local';

    $stmt = $conn->prepare(
        'INSERT INTO users (username, email, password, role, oauth_provider, oauth_id)
         VALUES (?, ?, NULL, \'user\', ?, ?)
         RETURNING *'
    );
    $stmt->execute([$username, $emailValue, $provider, $oauthId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function oauth_handle_google_callback($conn) {
    if (!isset($_GET['code'], $_GET['state']) || !oauth_verify_state('google', $_GET['state'])) {
        throw new RuntimeException('Invalid Google login session. Please try again.');
    }

    global $google_client_id, $google_client_secret;

    $token = oauth_http_request('POST', 'https://oauth2.googleapis.com/token', [
        'code' => $_GET['code'],
        'client_id' => $google_client_id,
        'client_secret' => $google_client_secret,
        'redirect_uri' => oauth_redirect_uri('google'),
        'grant_type' => 'authorization_code',
    ]);

    if (empty($token['access_token'])) {
        throw new RuntimeException('Google did not return an access token.');
    }

    $profile = oauth_http_request('GET', 'https://www.googleapis.com/oauth2/v2/userinfo', [], [
        'Authorization: Bearer ' . $token['access_token'],
    ]);

    if (empty($profile['id'])) {
        throw new RuntimeException('Unable to read your Google profile.');
    }

    $email = trim($profile['email'] ?? '');
    $name = trim($profile['name'] ?? 'Google User');

    $user = oauth_find_or_create_user($conn, 'google', $profile['id'], $email, $name);
    oauth_login_user($user);
}

function oauth_handle_facebook_callback($conn) {
    if (!isset($_GET['code'], $_GET['state']) || !oauth_verify_state('facebook', $_GET['state'])) {
        throw new RuntimeException('Invalid Facebook login session. Please try again.');
    }

    global $facebook_app_id, $facebook_app_secret;

    $token = oauth_http_request('GET', 'https://graph.facebook.com/v19.0/oauth/access_token', [
        'client_id' => $facebook_app_id,
        'client_secret' => $facebook_app_secret,
        'redirect_uri' => oauth_redirect_uri('facebook'),
        'code' => $_GET['code'],
    ]);

    if (empty($token['access_token'])) {
        throw new RuntimeException('Facebook did not return an access token.');
    }

    $profile = oauth_http_request('GET', 'https://graph.facebook.com/me', [
        'fields' => 'id,name,email',
        'access_token' => $token['access_token'],
    ]);

    if (empty($profile['id'])) {
        throw new RuntimeException('Unable to read your Facebook profile.');
    }

    $email = trim($profile['email'] ?? '');
    $name = trim($profile['name'] ?? 'Facebook User');

    if ($email === '') {
        throw new RuntimeException('Facebook did not provide an email. Allow email permission or use another sign-in method.');
    }

    $user = oauth_find_or_create_user($conn, 'facebook', $profile['id'], $email, $name);
    oauth_login_user($user);
}

function oauth_start_google() {
    global $google_client_id;

    $params = http_build_query([
        'client_id' => $google_client_id,
        'redirect_uri' => oauth_redirect_uri('google'),
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'state' => oauth_set_state('google'),
        'access_type' => 'online',
        'prompt' => 'select_account',
    ]);

    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    exit;
}

function oauth_start_facebook() {
    global $facebook_app_id;

    $params = http_build_query([
        'client_id' => $facebook_app_id,
        'redirect_uri' => oauth_redirect_uri('facebook'),
        'state' => oauth_set_state('facebook'),
        'scope' => 'email,public_profile',
        'response_type' => 'code',
    ]);

    header('Location: https://www.facebook.com/v19.0/dialog/oauth?' . $params);
    exit;
}
