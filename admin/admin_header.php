<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$adminPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="../image/logo 2.jpg">
</head>
<body class="admin-body">
<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-bus"></i>
            <span>Premium Bus</span>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link <?php echo $adminPage === 'dashboard' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="bookings.php" class="sidebar-link <?php echo $adminPage === 'bookings' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i> Bookings
            </a>
            <a href="users.php" class="sidebar-link <?php echo $adminPage === 'users' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Users
            </a>
            <a href="vehicles.php" class="sidebar-link <?php echo in_array($adminPage, ['vehicles', 'vehicle_edit'], true) ? 'active' : ''; ?>">
                <i class="fa-solid fa-car"></i> Vehicles
            </a>
            <div class="sidebar-divider"></div>
            <a href="../homepage.php" class="sidebar-link">
                <i class="fa-solid fa-globe"></i> View Site
            </a>
            <a href="../logout.php" class="sidebar-link sidebar-link-danger">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
            <div>
                <div class="sidebar-username"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="sidebar-role">Administrator</div>
            </div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle" type="button">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="admin-page-title"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></h1>
            <div class="admin-topbar-right">
                <span class="admin-date"><i class="fa-regular fa-calendar"></i> <?php echo date('M d, Y'); ?></span>
            </div>
        </header>

        <main class="admin-content">
        <?php
        if (isset($_SESSION['alert'])) {
            echo '<div class="admin-alert"><i class="fa-solid fa-circle-check"></i> ' . htmlspecialchars($_SESSION['alert']) . '</div>';
            unset($_SESSION['alert']);
        }
        ?>
