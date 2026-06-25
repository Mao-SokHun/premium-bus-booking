<?php include 'check_login.php'; ?>
<?php
include 'db.php';
include 'includes/booking_form.php';
include 'includes/vehicles.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Premium Car - Premium Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form_luxury.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="image/logo 2.jpg">
</head>
<body>
<?php include 'header.php'; ?>
<?php render_booking_form([
    'category' => 'premium',
    'icon' => 'fa-star',
    'title' => 'Premium Car Booking',
    'subtitle' => 'Book a premium vehicle for your group trip',
    'theme' => 'premium',
    'list_url' => 'list_premium.php',
    'vehicles' => get_vehicles($conn, 'premium'),
]); ?>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
