<?php
include 'check_admin.php';
include 'db.php';
include 'includes/helpers.php';

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$userId = $_SESSION['user_id'];
$isAdmin = is_admin();

$where = [];
$params = [];

if (!$isAdmin) {
    $where[] = "user_id = ?";
    $params[] = $userId;
}

if ($search !== '') {
    $where[] = "(name ILIKE ? OR phone ILIKE ? OR pickup_location ILIKE ? OR car_type ILIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($statusFilter !== '') {
    $where[] = "status = ?";
    $params[] = $statusFilter;
}

$sql = "SELECT * FROM bookings";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
$count = count($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="page-wrap page-inner">
    <div class="section-intro">
        <div class="section-num">—</div>
        <div class="section-intro-text">
            <div class="eyebrow">Booking History</div>
            <h2 class="page-title">My Bookings</h2>
            <p class="section-desc">View and manage all your vehicle reservations.</p>
        </div>
    </div>

    <div class="action-bar">
        <div class="action-bar-left">
            <a class="btn btn-gold" href="form_luxury.php"><i class="fa-solid fa-plus"></i> New Booking</a>
        </div>
        <form method="get" class="search-box">
            <input type="text" name="search" placeholder="Search bookings..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="confirmed" <?php echo $statusFilter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <button type="submit" class="btn-main"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="table-box">
        <div class="table-box-header">
            <h3><i class="fa-solid fa-calendar-check"></i> All Reservations</h3>
            <span class="count-badge"><?php echo $count; ?> total</span>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Customer</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Pax</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($count === 0) { ?>
                    <tr>
                        <td colspan="8" class="tbl-empty">
                            <div class="tbl-empty-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                            <p>No bookings found</p>
                            <a href="homepage.php">Book a vehicle now</a>
                        </td>
                    </tr>
                <?php } else { foreach ($data as $row) { ?>
                    <tr>
                        <td><span class="tbl-id">#<?php echo (int) $row['id']; ?></span></td>
                        <td><?php echo category_tag($row['category']); ?></td>
                        <td><span class="tbl-name"><?php echo htmlspecialchars($row['name']); ?></span></td>
                        <td>
                            <div class="tbl-route">
                                <div class="tbl-route-path">
                                    <span><?php echo htmlspecialchars($row['pickup_location']); ?></span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <span><?php echo htmlspecialchars($row['dropoff_location']); ?></span>
                                </div>
                                <span class="tbl-route-dates">
                                    <i class="fa-regular fa-calendar"></i>
                                    <?php echo htmlspecialchars($row['pickup_date']); ?> &mdash; <?php echo htmlspecialchars($row['dropoff_date']); ?>
                                </span>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($row['car_type']); ?></td>
                        <td><strong><?php echo (int) $row['passengers']; ?></strong></td>
                        <td><?php echo status_badge($row['status'] ?? 'pending'); ?></td>
                        <td><?php booking_table_actions($row, $row['category'], 'my_bookings.php'); ?></td>
                    </tr>
                <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
