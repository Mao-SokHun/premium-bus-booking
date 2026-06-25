<?php
include 'check_admin.php';
include 'db.php';

$id = $_POST['id'];
$type = $_POST['category'];
$redirect = $_POST['redirect'] ?? ('list_' . $type . '.php');

$sql = "DELETE FROM bookings WHERE id = ? AND category = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id, $type]);

$_SESSION['alert'] = 'Booking deleted';
header('Location: ' . $redirect);
exit;
