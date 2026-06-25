<?php
include 'check_login.php';
include 'db.php';

$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

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
    <title>Edit Profile - Premium Bus</title>
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
                <div class="form-card-icon premium"><i class="fa-solid fa-user-pen"></i></div>
                <h2>Edit profile</h2>
                <p>Update your username and email address</p>
            </div>
            <?php if ($msg !== '') { ?>
            <div class="profile-alert error" style="margin:0 32px 16px;"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($msg); ?></div>
            <?php } ?>
            <form action="profile_edit_submit.php" method="post" class="form-card-body">
                <div class="form-block">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-submit-row">
                    <button type="submit" class="btn-form-submit"><i class="fa-solid fa-check"></i> Save changes</button>
                    <div class="form-links"><a href="profile.php">Back to profile</a></div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
