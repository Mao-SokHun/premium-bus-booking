<?php
include '../check_admin.php';
include '../db.php';
include '../includes/helpers.php';

$pageTitle = 'Vehicles';

$categoryFilter = $_GET['category'] ?? '';
$sql = "SELECT * FROM vehicles";
$params = [];
if ($categoryFilter !== '' && in_array($categoryFilter, ['luxury', 'premium', 'air'], true)) {
    $sql .= " WHERE category = ?";
    $params[] = $categoryFilter;
}
$sql .= " ORDER BY category, sort_order, id";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$vehicles = $stmt->fetchAll();

$totalVehicles = (int) $conn->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

include 'admin_header.php';
?>

<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card stat-info">
        <div class="stat-icon"><i class="fa-solid fa-bus"></i></div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $totalVehicles; ?></div>
            <div class="stat-label">Total Vehicles</div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-plus"></i> Add New Vehicle</h3>
    </div>

    <?php if ($msg !== '') { ?>
    <div class="admin-alert" style="background:rgba(239,68,68,.12);color:#fca5a5;border-color:rgba(239,68,68,.28);margin-bottom:16px;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
    <?php } ?>

    <form action="vehicle_action.php" method="post" enctype="multipart/form-data" class="admin-edit-form">
            <input type="hidden" name="action" value="add">

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label>Category</label>
                    <select name="category" class="form-select" required>
                        <option value="luxury">Luxury</option>
                        <option value="premium">Premium</option>
                        <option value="air">Air Bus</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label>Vehicle name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Luxury Van Pro" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Short description for users" required></textarea>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label>Seats</label>
                    <input type="number" name="seats" class="form-control" min="1" placeholder="30" required>
                </div>
                <div class="admin-form-group">
                    <label>Sort order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" value="99">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label>Feature 1</label>
                    <div class="feature-pair">
                        <input type="text" name="feature1_icon" class="form-control" placeholder="fa-users" value="fa-users">
                        <input type="text" name="feature1_text" class="form-control" placeholder="45 seats">
                    </div>
                </div>
                <div class="admin-form-group">
                    <label>Feature 2</label>
                    <div class="feature-pair">
                        <input type="text" name="feature2_icon" class="form-control" placeholder="fa-snowflake" value="fa-snowflake">
                        <input type="text" name="feature2_text" class="form-control" placeholder="A/C">
                    </div>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Car image</label>
                <input type="file" name="image_file" class="form-control" accept="image/*">
                <span class="admin-form-hint">Upload JPG, PNG, or WEBP — or use image path below</span>
            </div>

            <div class="admin-form-group">
                <label>Image path <span class="admin-form-hint">(if not uploading)</span></label>
                <input type="text" name="image_path" class="form-control" placeholder="image/luxury_van.jpg">
            </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin"><i class="fa-solid fa-check"></i> Add vehicle</button>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-car"></i> Fleet (<?php echo count($vehicles); ?>)</h3>
        <form method="get" class="filter-form" style="margin:0;">
            <select name="category" class="form-select" style="min-width:140px;" onchange="this.form.submit()">
                <option value="">All categories</option>
                <option value="luxury" <?php echo $categoryFilter === 'luxury' ? 'selected' : ''; ?>>Luxury</option>
                <option value="premium" <?php echo $categoryFilter === 'premium' ? 'selected' : ''; ?>>Premium</option>
                <option value="air" <?php echo $categoryFilter === 'air' ? 'selected' : ''; ?>>Air Bus</option>
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th><th>Name</th><th>Category</th><th>Seats</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($vehicles) === 0) { ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No vehicles yet. Add one above.</td></tr>
            <?php } else { foreach ($vehicles as $v) { ?>
                <tr>
                    <td>
                        <img src="../<?php echo htmlspecialchars($v['image_path']); ?>" alt="" class="vehicle-thumb" onerror="this.src='../image/logo 2.jpg'">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($v['name']); ?></strong>
                        <small class="d-block text-muted"><?php echo htmlspecialchars(strlen($v['description']) > 50 ? substr($v['description'], 0, 50) . '...' : $v['description']); ?></small>
                    </td>
                    <td><span class="cat-tag cat-<?php echo $v['category']; ?>"><?php echo category_label($v['category']); ?></span></td>
                    <td><?php echo (int) $v['seats']; ?></td>
                    <td>
                        <?php if ($v['is_active']) { ?>
                            <span class="status-badge badge-confirmed">Active</span>
                        <?php } else { ?>
                            <span class="status-badge badge-cancelled">Hidden</span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="action-cell">
                            <a href="vehicle_edit.php?id=<?php echo $v['id']; ?>" class="btn-admin-xs btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                            <form action="vehicle_action.php" method="post" class="d-inline">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="vehicle_id" value="<?php echo $v['id']; ?>">
                                <button type="submit" class="btn-admin-xs"><?php echo $v['is_active'] ? 'Hide' : 'Show'; ?></button>
                            </form>
                            <form action="vehicle_action.php" method="post" class="d-inline" onsubmit="return confirm('Delete this vehicle?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="vehicle_id" value="<?php echo $v['id']; ?>">
                                <button type="submit" class="btn-admin-xs btn-delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
