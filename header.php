<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$msg = '';
if (isset($_SESSION['alert'])) {
    $msg = $_SESSION['alert'];
    unset($_SESSION['alert']);
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$currentPage = basename($_SERVER['PHP_SELF']);
$isHome = $currentPage === 'homepage.php';
?>
<header class="Header-Full<?php echo $isHome ? ' nav-over-hero' : ' scrolled'; ?>">
  <div class="header">
    <a href="homepage.php" class="brand">
      <span class="brand-mark"><i class="fa-solid fa-bus"></i></span>
      <span class="brand-text">
        <span class="brand-name">Premium Bus</span>
        <span class="brand-tagline">Cambodia</span>
      </span>
    </a>
    <button class="nav-toggle" id="navToggle" type="button" aria-label="Menu">
      <i class="fa-solid fa-bars"></i>
    </button>
    <nav class="header-right" id="navMenu">
      <a href="homepage.php" class="<?php echo $currentPage === 'homepage.php' ? 'nav-active' : ''; ?>">Home</a>
      <a href="view_luxury_bus.php" class="<?php echo $currentPage === 'view_luxury_bus.php' ? 'nav-active' : ''; ?>">Luxury</a>
      <a href="view_premium_bus.php" class="<?php echo $currentPage === 'view_premium_bus.php' ? 'nav-active' : ''; ?>">Premium</a>
      <a href="view_air_bus.php" class="<?php echo $currentPage === 'view_air_bus.php' ? 'nav-active' : ''; ?>">Air Bus</a>
      <?php if ($isAdmin) { ?>
      <div class="dropdown d-inline">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Bookings</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="my_bookings.php"><i class="fa-solid fa-list"></i> All</a></li>
          <li><a class="dropdown-item" href="list_luxury.php"><i class="fa-solid fa-crown"></i> Luxury</a></li>
          <li><a class="dropdown-item" href="list_premium.php"><i class="fa-solid fa-star"></i> Premium</a></li>
          <li><a class="dropdown-item" href="list_air.php"><i class="fa-solid fa-bus"></i> Air Bus</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="admin/bookings.php"><i class="fa-solid fa-gear"></i> Admin panel</a></li>
        </ul>
      </div>
      <a href="admin/dashboard.php" class="nav-admin"><i class="fa-solid fa-chart-line"></i> Admin</a>
      <?php } ?>
      <?php if (isset($_SESSION['username'])) { ?>
        <a href="profile.php" class="user-pill<?php echo $currentPage === 'profile.php' || strpos($currentPage, 'profile_') === 0 ? ' nav-active' : ''; ?>">
          <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
          <?php echo htmlspecialchars($_SESSION['username']); ?>
        </a>
        <a href="logout.php">Logout</a>
      <?php } else { ?>
        <a href="login.php" class="btn-sign">Sign In</a>
      <?php } ?>
    </nav>
  </div>
</header>
<?php if ($msg != '') { ?>
<div class="alert-box">
  <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
</div>
<?php } ?>
<script>
document.getElementById('navToggle')?.addEventListener('click', function() {
  document.getElementById('navMenu').classList.toggle('open');
});
window.addEventListener('scroll', function() {
  var nav = document.querySelector('.Header-Full.nav-over-hero');
  if (nav) nav.classList.toggle('scrolled', window.scrollY > 60);
});
</script>
