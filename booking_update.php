<?php
include 'check_admin.php';
include 'db.php';

$id = $_POST['id'];
$type = $_POST['category'];
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$pickup = trim($_POST['pickup_location']);
$pickdate = $_POST['pickup_date'];
$dropoff = trim($_POST['dropoff_location']);
$dropdate = $_POST['dropoff_date'];
$cartype = $_POST['car_type'];
$passenger = $_POST['passengers'];

$sql = "UPDATE bookings SET name=?, phone=?, pickup_location=?, pickup_date=?, dropoff_location=?, dropoff_date=?, car_type=?, passengers=?
        WHERE id=? AND category=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$name, $phone, $pickup, $pickdate, $dropoff, $dropdate, $cartype, $passenger, $id, $type]);

$_SESSION['alert'] = 'Booking updated successfully';
header('Location: my_bookings.php');
exit;
