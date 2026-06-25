<?php
include '../check_admin.php';
include '../db.php';
include '../includes/helpers.php';

$pageTitle = 'Bookings';

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(b.name ILIKE ? OR b.phone ILIKE ? OR CAST(b.id AS TEXT) = ? OR b.pickup_location ILIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = $search;
    $params[] = "%$search%";
}
if ($statusFilter !== '') {
    $where[] = "b.status = ?";
    $params[] = $statusFilter;
}
if ($categoryFilter !== '') {
    $where[] = "b.category = ?";
    $params[] = $categoryFilter;
}

$sql = "SELECT b.*, u.username FROM bookings b LEFT JOIN users u ON b.user_id = u.id";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY b.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

include 'admin_header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-filter"></i> Filter Bookings</h3>
    </div>
    <form method="get" class="filter-form">
        <div class="filter-row">
            <input type="text" name="search" class="form-control" placeholder="Search name, phone, ID, location..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo $statusFilter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="luxury" <?php echo $categoryFilter === 'luxury' ? 'selected' : ''; ?>>Luxury</option>
                <option value="premium" <?php echo $categoryFilter === 'premium' ? 'selected' : ''; ?>>Premium</option>
                <option value="air" <?php echo $categoryFilter === 'air' ? 'selected' : ''; ?>>Air Bus</option>
            </select>
            <button type="submit" class="btn-admin"><i class="fa-solid fa-search"></i> Search</button>
            <a href="bookings.php" class="btn-admin-outline">Reset</a>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-list"></i> All Bookings (<?php echo count($data); ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Customer</th><th>Phone</th><th>Category</th>
                    <th>Pick-Up</th><th>Drop-Off</th><th>Car</th><th>Pax</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($data) == 0) { ?>
                <tr><td colspan="10" class="text-center text-muted py-4">No bookings found</td></tr>
            <?php } else { foreach ($data as $row) { ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        <small class="d-block text-muted">@<?php echo htmlspecialchars($row['username'] ?? 'guest'); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><span class="cat-tag cat-<?php echo $row['category']; ?>"><?php echo category_label($row['category']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['pickup_location']); ?><br><small><?php echo $row['pickup_date']; ?></small></td>
                    <td><?php echo htmlspecialchars($row['dropoff_location']); ?><br><small><?php echo $row['dropoff_date']; ?></small></td>
                    <td><?php echo htmlspecialchars($row['car_type']); ?></td>
                    <td><?php echo $row['passengers']; ?></td>
                    <td>
                        <form action="booking_status.php" method="post" class="status-form">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                                <option value="pending" <?php echo ($row['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo ($row['status'] ?? '') === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo ($row['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo ($row['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="action-cell">
                        <a href="../edit_booking.php?id=<?php echo $row['id']; ?>&type=<?php echo $row['category']; ?>" class="btn-admin-xs btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                        <form action="../booking_delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this booking?');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="category" value="<?php echo $row['category']; ?>">
                            <input type="hidden" name="redirect" value="admin/bookings.php">
                            <button type="submit" class="btn-admin-xs btn-delete" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
