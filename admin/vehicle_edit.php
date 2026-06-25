<?php
include '../check_admin.php';
include '../db.php';

$vehicleId = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$vehicleId]);
$vehicle = $stmt->fetch();

if (!$vehicle) {
    $_SESSION['alert'] = 'Vehicle not found';
    header('Location: vehicles.php');
    exit;
}

$pageTitle = 'Edit Vehicle';
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

include 'admin_header.php';
?>

<div class="admin-card" style="max-width:720px;">
    <div class="admin-card-header">
        <h3><i class="fa-solid fa-pen"></i> Edit Vehicle</h3>
        <a href="vehicles.php" class="btn-admin-outline btn-admin-sm">Back</a>
    </div>

    <?php if ($msg !== '') { ?>
    <div class="admin-alert" style="background:rgba(239,68,68,.12);color:#fca5a5;border-color:rgba(239,68,68,.28);">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
    <?php } ?>

    <div class="vehicle-edit-preview">
        <img src="../<?php echo htmlspecialchars($vehicle['image_path']); ?>" alt="">
    </div>

    <form action="vehicle_action.php" method="post" enctype="multipart/form-data" class="admin-edit-form">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label>Category</label>
                <select name="category" class="form-select" required>
                    <option value="luxury" <?php echo $vehicle['category'] === 'luxury' ? 'selected' : ''; ?>>Luxury</option>
                    <option value="premium" <?php echo $vehicle['category'] === 'premium' ? 'selected' : ''; ?>>Premium</option>
                    <option value="air" <?php echo $vehicle['category'] === 'air' ? 'selected' : ''; ?>>Air Bus</option>
                </select>
            </div>
            <div class="admin-form-group">
                <label>Vehicle name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($vehicle['name']); ?>" required>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2" required><?php echo htmlspecialchars($vehicle['description']); ?></textarea>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label>Seats</label>
                <input type="number" name="seats" class="form-control" min="1" value="<?php echo (int) $vehicle['seats']; ?>" required>
            </div>
            <div class="admin-form-group">
                <label>Sort order</label>
                <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo (int) $vehicle['sort_order']; ?>">
            </div>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label>Feature 1</label>
                <div class="feature-pair">
                    <input type="text" name="feature1_icon" class="form-control" value="<?php echo htmlspecialchars($vehicle['feature1_icon'] ?? 'fa-users'); ?>">
                    <input type="text" name="feature1_text" class="form-control" value="<?php echo htmlspecialchars($vehicle['feature1_text'] ?? ''); ?>">
                </div>
            </div>
            <div class="admin-form-group">
                <label>Feature 2</label>
                <div class="feature-pair">
                    <input type="text" name="feature2_icon" class="form-control" value="<?php echo htmlspecialchars($vehicle['feature2_icon'] ?? 'fa-snowflake'); ?>">
                    <input type="text" name="feature2_text" class="form-control" value="<?php echo htmlspecialchars($vehicle['feature2_text'] ?? ''); ?>">
                </div>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Replace image</label>
            <input type="file" name="image_file" class="form-control" accept="image/*">
        </div>

        <div class="admin-form-group">
            <label>Image path</label>
            <input type="text" name="image_path" class="form-control" value="<?php echo htmlspecialchars($vehicle['image_path']); ?>">
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin"><i class="fa-solid fa-check"></i> Save changes</button>
            <a href="vehicles.php" class="btn-admin-outline">Cancel</a>
        </div>
    </form>
</div>

<?php include 'admin_footer.php'; ?>
