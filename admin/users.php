<?php
include '../check_admin.php';
include '../db.php';
include '../includes/helpers.php';

$pageTitle = 'Users';

$users = $conn->query("SELECT id, username, email, role, created_at,
    (SELECT COUNT(*) FROM bookings WHERE user_id = users.id) as booking_count
    FROM users ORDER BY id ASC")->fetchAll();

include 'admin_header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-users"></i> All Users (<?php echo count($users); ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Bookings</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td>#<?php echo $user['id']; ?></td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar-sm"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <?php if ($user['role'] === 'admin') { ?>
                            <span class="role-badge role-admin">Admin</span>
                        <?php } else { ?>
                            <span class="role-badge role-user">User</span>
                        <?php } ?>
                    </td>
                    <td><?php echo $user['booking_count']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <div class="action-cell">
                            <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn-admin-xs btn-edit" title="Edit">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']) { ?>
                            <?php if ($user['role'] === 'admin') { ?>
                            <form action="user_action.php" method="post" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="demote">
                                <button type="submit" class="btn-admin-xs" onclick="return confirm('Remove admin role?');">Demote</button>
                            </form>
                            <?php } else { ?>
                            <form action="user_action.php" method="post" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="promote">
                                <button type="submit" class="btn-admin-xs" onclick="return confirm('Promote to admin?');">Promote</button>
                            </form>
                            <form action="user_action.php" method="post" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-admin-xs btn-delete" onclick="return confirm('Delete this user and all their bookings?');"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            <?php } ?>
                            <?php } else { ?>
                            <span class="text-muted">You</span>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
