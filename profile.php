<?php
include 'check_login.php';
include 'db.php';

$stmt = $conn->prepare("SELECT id, username, email, role, oauth_provider, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: logout.php');
    exit;
}

$msg = '';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="image/logo 2.jpg">
</head>
<body>
<?php include 'header.php'; ?>

<div class="page-wrap page-inner">
    <div class="section-intro">
        <div class="section-num"><i class="fa-solid fa-user" style="font-size:48px;-webkit-text-stroke:0;color:var(--gold);"></i></div>
        <div class="section-intro-text">
            <div class="eyebrow">Account</div>
            <h2 class="page-title">My Profile</h2>
            <p class="section-desc">Manage your account details and security settings.</p>
        </div>
    </div>

    <?php if ($msg !== '') { ?>
    <div class="profile-alert success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($msg); ?></div>
    <?php } ?>

    <div class="profile-grid">
        <div class="profile-card">
            <div class="profile-card-head">
                <div class="profile-avatar-lg"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                <div>
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div class="profile-details">
                <div class="profile-detail-row">
                    <span>Role</span>
                    <strong><?php echo ucfirst(htmlspecialchars($user['role'])); ?></strong>
                </div>
                <div class="profile-detail-row">
                    <span>Member since</span>
                    <strong><?php echo date('M j, Y', strtotime($user['created_at'])); ?></strong>
                </div>
                <?php if (!empty($user['oauth_provider'])) { ?>
                <div class="profile-detail-row">
                    <span>Sign-in</span>
                    <strong><?php echo ucfirst(htmlspecialchars($user['oauth_provider'])); ?></strong>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="profile-actions-card">
            <h4>Account settings</h4>
            <a href="profile_edit.php" class="profile-action-btn">
                <i class="fa-solid fa-pen"></i>
                <span>Edit profile</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <a href="profile_password.php" class="profile-action-btn">
                <i class="fa-solid fa-key"></i>
                <span>Change password</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <a href="view_luxury_bus.php" class="profile-action-btn">
                <i class="fa-solid fa-bus"></i>
                <span>Browse & book vehicles</span>
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <?php if ($user['role'] !== 'admin') { ?>
            <form action="profile_delete.php" method="post" class="profile-delete-form" onsubmit="return confirm('Delete your account permanently? This cannot be undone.');">
                <button type="submit" class="profile-action-btn danger">
                    <i class="fa-solid fa-trash"></i>
                    <span>Delete account</span>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
