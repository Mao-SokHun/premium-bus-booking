<?php
include 'check_login.php';
include 'db.php';

$stmt = $conn->prepare("SELECT password, oauth_provider FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$hasPassword = !empty($user['password']);

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
    <title>Reset Password - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form_luxury.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="form-page">
    <div class="form-page-inner">
        <div class="form-card">
            <div class="form-card-head">
                <div class="form-card-icon premium"><i class="fa-solid fa-key"></i></div>
                <h2><?php echo $hasPassword ? 'Reset password' : 'Set password'; ?></h2>
                <p><?php echo $hasPassword ? 'Enter your current password and choose a new one' : 'Create a password for your account'; ?></p>
            </div>
            <?php if ($msg !== '') { ?>
            <div class="profile-alert error" style="margin:0 32px 16px;"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($msg); ?></div>
            <?php } ?>
            <form action="profile_password_submit.php" method="post" class="form-card-body">
                <?php if ($hasPassword) { ?>
                <div class="form-block">
                    <label class="field-label">Current password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <?php } ?>
                <div class="form-block">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">New password</label>
                            <input type="password" name="new_password" class="form-control" minlength="6" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Confirm new password</label>
                            <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                        </div>
                    </div>
                </div>
                <div class="form-submit-row">
                    <button type="submit" class="btn-form-submit"><i class="fa-solid fa-check"></i> Update password</button>
                    <div class="form-links"><a href="profile.php">Back to profile</a></div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
