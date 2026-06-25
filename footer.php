<footer class="footer">
  <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
  <div class="footer-content">
    <div class="footer-section">
      <span class="brand-name">Premium Bus</span>
      <p>Curated vehicle rental across Cambodia. Luxury, premium, and air bus — each journey crafted with care.</p>
      <div class="social-links">
        <a href="https://www.facebook.com/share/1H9mzNxRCi/?mibextid=wwXIfr" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="https://t.me/DRBOSS2212" target="_blank"><i class="fa-brands fa-telegram"></i></a>
        <a href="mailto:moungdaro5657@gmail.com"><i class="fa-solid fa-envelope"></i></a>
      </div>
    </div>
    <div class="footer-section">
      <h2>Fleet</h2>
      <ul>
        <li><a href="view_luxury_bus.php">Luxury Class</a></li>
        <li><a href="view_premium_bus.php">Premium Class</a></li>
        <li><a href="view_air_bus.php">Air Bus Class</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h2>Account</h2>
      <ul>
        <?php if (isset($_SESSION['user_id'])) { ?>
        <li><a href="profile.php">My Profile</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
        <li><a href="my_bookings.php">All Bookings</a></li>
        <?php } ?>
        <li><a href="logout.php">Logout</a></li>
        <?php } else { ?>
        <li><a href="login.php">Sign In</a></li>
        <li><a href="index.php">Register</a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="footer-section">
      <h2>Contact</h2>
      <ul>
        <li>333 St. Mao Setung</li>
        <li>Phnom Penh, Cambodia</li>
        <li>+855 97 49 44 390</li>
        <li><a href="mailto:moungdaro5657@gmail.com">moungdaro5657@gmail.com</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">&copy; <?php echo date('Y'); ?> Premium Bus — Crafted by Muong Keopichrundaro</div>
</footer>
