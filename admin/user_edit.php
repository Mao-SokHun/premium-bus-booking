<?php
include '../check_admin.php';
include '../db.php';
include '../includes/helpers.php';

$userId = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['alert'] = 'User not found';
    header('Location: users.php');
    exit;
}

$isSelf = $userId === (int) $_SESSION['user_id'];
$pageTitle = 'Edit User';
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

include 'admin_header.php';
?>

<div class="admin-card" style="max-width:640px;">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-user-pen"></i> Edit User #<?php echo $user['id']; ?></h3>
        <a href="users.php" class="btn-admin-outline btn-admin-sm">Back</a>
    </div>

    <?php if ($msg !== '') { ?>
    <div class="admin-alert" style="background:rgba(239,68,68,.12);color:#fca5a5;border-color:rgba(239,68,68,.28);">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
    <?php } ?>

    <form action="user_edit_submit.php" method="post" class="admin-edit-form">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

        <div class="admin-form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="admin-form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="admin-form-group">
            <label>Role</label>
            <?php if ($isSelf) { ?>
            <input type="text" class="form-control" value="Admin (you)" disabled>
            <input type="hidden" name="role" value="admin">
            <?php } else { ?>
            <select name="role" class="form-select">
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <?php } ?>
        </div>

        <div class="admin-form-group">
            <label>New password <span class="admin-form-hint">(leave blank to keep current)</span></label>
            <input type="password" name="new_password" class="form-control" minlength="6" placeholder="Min. 6 characters">
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin"><i class="fa-solid fa-check"></i> Save changes</button>
            <a href="users.php" class="btn-admin-outline">Cancel</a>
        </div>
    </form>
</div>

<?php include 'admin_footer.php'; ?>
