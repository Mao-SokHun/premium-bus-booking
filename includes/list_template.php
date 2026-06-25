<?php
function render_booking_list($category, $categoryLabel, $formPage) {
    include 'check_admin.php';
    include 'db.php';
    include 'includes/helpers.php';

    $search = trim($_GET['search'] ?? '');
    $userId = $_SESSION['user_id'];
    $isAdmin = is_admin();

    $where = ["category = ?"];
    $params = [$category];

    if (!$isAdmin) {
        $where[] = "user_id = ?";
        $params[] = $userId;
    }
    if ($search !== '') {
        $where[] = "(name ILIKE ? OR phone ILIKE ? OR pickup_location ILIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql = "SELECT * FROM bookings WHERE " . implode(' AND ', $where) . " ORDER BY id DESC";
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
    <title><?php echo $categoryLabel; ?> Bookings - Premium Bus</title>
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
            <div class="eyebrow"><?php echo $categoryLabel; ?></div>
            <h2 class="page-title"><?php echo $categoryLabel; ?> Bookings</h2>
            <p class="section-desc">Manage your <?php echo strtolower($categoryLabel); ?> reservations.</p>
        </div>
    </div>

    <div class="action-bar">
        <div class="action-bar-left">
            <a class="btn btn-gold" href="<?php echo $formPage; ?>"><i class="fa-solid fa-plus"></i> New Booking</a>
            <a class="btn btn-outline" href="my_bookings.php"><i class="fa-solid fa-list"></i> All Bookings</a>
        </div>
        <form method="get" class="search-box">
            <input type="text" name="search" placeholder="Search name, phone, location..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-main"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="table-box">
        <div class="table-box-header">
            <h3><i class="fa-solid fa-table-list"></i> Booking Records</h3>
            <span class="count-badge"><?php echo $count; ?> total</span>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
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
                        <td colspan="7" class="tbl-empty">
                            <div class="tbl-empty-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                            <p>No bookings yet</p>
                            <a href="<?php echo $formPage; ?>">Create your first booking</a>
                        </td>
                    </tr>
                <?php } else { foreach ($data as $row) { ?>
                    <tr>
                        <td><span class="tbl-name"><?php echo htmlspecialchars($row['name']); ?></span></td>
                        <td><span class="tbl-muted"><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></span></td>
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
                        <td><?php booking_table_actions($row, $category); ?></td>
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
<?php } ?>
