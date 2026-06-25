<?php
include '../check_admin.php';
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: bookings.php');
    exit;
}

$id = $_POST['id'];
$status = $_POST['status'];
$allowed = ['pending', 'confirmed', 'completed', 'cancelled'];

if (!in_array($status, $allowed)) {
    $_SESSION['alert'] = 'Invalid status';
    header('Location: bookings.php');
    exit;
}

$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->execute([$status, $id]);

$_SESSION['alert'] = 'Booking status updated to ' . $status;
header('Location: bookings.php');
exit;
