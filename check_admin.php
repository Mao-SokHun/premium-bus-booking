<?php
include 'check_login.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['alert'] = 'Admin access required';
    header('Location: homepage.php');
    exit;
}
