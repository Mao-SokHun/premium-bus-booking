<?php include 'check_login.php'; ?>
<?php
include 'db.php';
include 'includes/helpers.php';
include 'includes/vehicles.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Car - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/view_luxury_bus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="image/logo 2.jpg">
</head>
<body>
<?php include 'header.php'; ?>

<div class="page-wrap view-page page-inner">
    <div class="section-header center">
        <div class="section-eyebrow"><i class="fa-solid fa-crown"></i> Luxury Fleet</div>
        <h2 class="page-title">Luxury Vehicles</h2>
        <p class="section-desc">Premium comfort for group travel and special events.</p>
    </div>
    <div class="row g-4 fleet-grid">
        <?php render_vehicle_cards_from_db($conn, 'luxury', 'form_luxury.php'); ?>
    </div>
    <div class="text-center mt-5">
        <a class="btn btn-outline" href="homepage.php"><i class="fa-solid fa-arrow-left"></i> Back Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
