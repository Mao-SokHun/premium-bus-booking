<?php
require_once __DIR__ . '/oauth.php';

$showGoogle = oauth_provider_enabled('google');
$showFacebook = oauth_provider_enabled('facebook');
$anyEnabled = $showGoogle || $showFacebook;
?>

<?php if ($anyEnabled) { ?>
<div class="social-login">
    <div class="social-divider"><span>or continue with</span></div>
    <div class="social-buttons">
        <?php if ($showGoogle) { ?>
        <a href="auth/google.php" class="social-btn social-google">
            <i class="fa-brands fa-google"></i> Google
        </a>
        <?php } ?>
        <?php if ($showFacebook) { ?>
        <a href="auth/facebook.php" class="social-btn social-facebook">
            <i class="fa-brands fa-facebook-f"></i> Facebook
        </a>
        <?php } ?>
    </div>
</div>
<?php } elseif (!empty($google_enabled) || !empty($facebook_enabled)) { ?>
<div class="oauth-setup-hint">
    Social login is enabled in config but API keys are missing. Add your credentials in
    <code>config.php</code> (Google Client ID/Secret and Facebook App ID/Secret).
</div>
<?php } ?>
