<?php
include 'check_login.php';
include 'db.php';
include 'includes/helpers.php';
include 'includes/vehicles.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: homepage.php');
    exit;
}

$type = $_POST['category'];
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$pickup = trim($_POST['pickup_location']);
$pickdate = $_POST['pickup_date'];
$dropoff = trim($_POST['dropoff_location']);
$dropdate = $_POST['dropoff_date'];
$vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
$passenger = $_POST['passengers'];

if ($name == '' || $phone == '' || $pickup == '' || $pickdate == '' || $dropoff == '' || $dropdate == '' || $vehicleId <= 0 || $passenger == '') {
    $_SESSION['alert'] = 'Please fill all fields';
    header('Location: form_' . $type . '.php');
    exit;
}

$vehicle = get_vehicle_by_id($conn, $vehicleId, $type);
if (!$vehicle) {
    $_SESSION['alert'] = 'Please select a valid vehicle';
    header('Location: form_' . $type . '.php');
    exit;
}

$userId = $_SESSION['user_id'];
$cartype = $vehicle['name'];

$sql = "INSERT INTO bookings (user_id, category, name, phone, pickup_location, pickup_date, dropoff_location, dropoff_date, car_type, vehicle_id, passengers, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId, $type, $name, $phone, $pickup, $pickdate, $dropoff, $dropdate, $cartype, $vehicleId, $passenger]);

$_SESSION['alert'] = 'Booking submitted! We will confirm shortly.';
header('Location: ' . (is_admin() ? 'my_bookings.php' : 'homepage.php'));
exit;
