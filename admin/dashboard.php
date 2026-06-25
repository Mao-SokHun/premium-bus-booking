<?php
include '../check_admin.php';
include '../db.php';
include '../includes/helpers.php';

$pageTitle = 'Dashboard';

$totalBookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$pendingBookings = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
$confirmedBookings = $conn->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn();

$luxuryCount = $conn->query("SELECT COUNT(*) FROM bookings WHERE category = 'luxury'")->fetchColumn();
$premiumCount = $conn->query("SELECT COUNT(*) FROM bookings WHERE category = 'premium'")->fetchColumn();
$airCount = $conn->query("SELECT COUNT(*) FROM bookings WHERE category = 'air'")->fetchColumn();
$totalVehicles = $conn->query("SELECT COUNT(*) FROM vehicles WHERE is_active = true")->fetchColumn();

$recentBookings = $conn->query("
    SELECT b.*, u.username
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    ORDER BY b.created_at DESC LIMIT 8
")->fetchAll();

include 'admin_header.php';
?>

<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $totalBookings; ?></div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>
    <div class="stat-card stat-warning">
        <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $pendingBookings; ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card stat-success">
        <div class="stat-icon"><i class="fa-solid fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $confirmedBookings; ?></div>
            <div class="stat-label">Confirmed</div>
        </div>
    </div>
    <div class="stat-card stat-info">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Registered Users</div>
        </div>
    </div>
    <div class="stat-card stat-primary">
        <div class="stat-icon"><i class="fa-solid fa-bus"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $totalVehicles; ?></div>
            <div class="stat-label">Active Vehicles</div>
        </div>
    </div>
</div>

<div class="admin-grid-2">
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Bookings by Category</h3>
        </div>
        <div class="category-bars">
            <div class="category-bar-item">
                <div class="category-bar-label"><i class="fa-solid fa-crown"></i> Luxury</div>
                <div class="category-bar-track">
                    <div class="category-bar-fill bar-luxury" style="width: <?php echo $totalBookings > 0 ? round($luxuryCount / $totalBookings * 100) : 0; ?>%"></div>
                </div>
                <span class="category-bar-count"><?php echo $luxuryCount; ?></span>
            </div>
            <div class="category-bar-item">
                <div class="category-bar-label"><i class="fa-solid fa-star"></i> Premium</div>
                <div class="category-bar-track">
                    <div class="category-bar-fill bar-premium" style="width: <?php echo $totalBookings > 0 ? round($premiumCount / $totalBookings * 100) : 0; ?>%"></div>
                </div>
                <span class="category-bar-count"><?php echo $premiumCount; ?></span>
            </div>
            <div class="category-bar-item">
                <div class="category-bar-label"><i class="fa-solid fa-bus"></i> Air Bus</div>
                <div class="category-bar-track">
                    <div class="category-bar-fill bar-air" style="width: <?php echo $totalBookings > 0 ? round($airCount / $totalBookings * 100) : 0; ?>%"></div>
                </div>
                <span class="category-bar-count"><?php echo $airCount; ?></span>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3><i class="fa-solid fa-bolt"></i> Quick Actions</h3>
        </div>
        <div class="quick-actions">
            <a href="bookings.php?status=pending" class="quick-action-btn">
                <i class="fa-solid fa-hourglass-half"></i>
                <span>Review Pending</span>
                <span class="quick-badge"><?php echo $pendingBookings; ?></span>
            </a>
            <a href="bookings.php" class="quick-action-btn">
                <i class="fa-solid fa-list"></i>
                <span>All Bookings</span>
            </a>
            <a href="users.php" class="quick-action-btn">
                <i class="fa-solid fa-user-gear"></i>
                <span>Manage Users</span>
            </a>
            <a href="vehicles.php" class="quick-action-btn quick-action-primary">
                <i class="fa-solid fa-car"></i>
                <span>Add Vehicle</span>
            </a>
            <a href="../form_luxury.php" class="quick-action-btn">
                <i class="fa-solid fa-plus"></i>
                <span>New Booking</span>
            </a>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Bookings</h3>
        <a href="bookings.php" class="btn-admin-sm">View All</a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th><th>Customer</th><th>Category</th><th>Route</th><th>Date</th><th>Status</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($recentBookings) == 0) { ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No bookings yet</td></tr>
            <?php } else { foreach ($recentBookings as $row) { ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        <small class="d-block text-muted"><?php echo htmlspecialchars($row['username'] ?? 'N/A'); ?></small>
                    </td>
                    <td><span class="cat-tag cat-<?php echo $row['category']; ?>"><?php echo category_label($row['category']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['pickup_location']); ?> &rarr; <?php echo htmlspecialchars($row['dropoff_location']); ?></td>
                    <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                    <td><?php echo status_badge($row['status'] ?? 'pending'); ?></td>
                    <td>
                        <a href="bookings.php?search=<?php echo $row['id']; ?>" class="btn-admin-xs">View</a>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
