<!DOCTYPE html>
<html>


<?php
require_once __DIR__ . "/config/db.php";

?>


<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KUET Debating Society (KDS)</title>

  <!-- Google Fonts (ONLY ONCE) -->
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&display=swap" rel="stylesheet">

  <!-- CSS -->
<link rel="stylesheet" href="./style.css">
</head>
<body>
<script src="script.js"></script>
<!-- HEADER (fixed part) -->
<header class="header">
    <!-- Topbar -->
    <div class="topbar">
      <div class="topbar-left">
        <a href="index.php?page=home">
          <i class="fa-solid fa-house"></i> Home
        </a>
        <a href="index.php?page=role_select_signin">
          <i class="fa-solid fa-user-plus"></i> Join Now
        </a> 
        <a href="index.php?page=role_select_login">
          <i class="fa-solid fa-right-to-bracket"></i> Log In
        </a>
        <a href="index.php">
          <i class="fa-solid fa-phone"></i> Contact
        </a>

      </div>

      <div class="topbar-right">
        <a href="https://www.facebook.com/groups/kds.kuet/" target="_blank">
          <i class="fa-brands fa-facebook"></i> Facebook
        </a>
        <a href="#" target="_blank">
          <i class="fa-brands fa-linkedin"></i> LinkedIn
        </a>
        <a href="mailto:example@gmail.com">
          <i class="fa-solid fa-envelope"></i> Email
        </a>
        <a href="#members-section" target="_blank" id="profile-icon">
          <i class="fa-solid fa-user"></i> 
        </a>
        <a href="#" class="theme-toggle" id="theme-toggle" title="Toggle Theme">
          <i class="fas fa-moon"></i>
        </a>
      </div>
    </div>

    <div class="main-header" id="main-header">
      <!-- Logo -->
      <div class="logo-section">
        <div class="logo">
          <span class="logo-kuet">KUET</span>
          <span class="logo-text">Debating Society</span>
          <span class="logo-kds">KDS</span>
        </div>
      </div>

      <nav class="navbar">
        <div class="nav-div" id="nav-div">
          <ul class="nav-list">

            <li class="nav-item">
              <span class="nav-link">Events</span>
              <ul class="dropdown">
                <li><a href="#">Upcoming Events</a></li>
                <li><a href="#">Past Events</a></li>
              </ul>
            </li>

            <li class="nav-item">
              <span class="nav-link">Debates</span>
              <ul class="dropdown">
                <li><a href="#">Inter KUET Debate</a></li>
                <li><a href="#">Workshops</a></li>
              </ul>
            </li>

            <li class="nav-item">
              <span class="nav-link">Membership</span>
              <ul class="dropdown">
                <li><a href="#">Join KDS</a></li>
                <li><a href="#">Member List</a></li>
              </ul>
            </li>

            <li class="nav-item">
              <span class="nav-link">Visit</span>
              <ul class="dropdown">
                <li><a href="#">Campus Tour</a></li>
                <li><a href="#">Location</a></li>
              </ul>
            </li>

            <li class="nav-item">
              <span class="nav-link">Resources</span>
              <ul class="dropdown">
                <li><a href="#">Blog</a></li>
                <li><a href="#">Materials</a></li>
              </ul>
            </li>

          </ul>
        </div>
      </nav>
    </div>


</header>

<hr>

<!-- DYNAMIC PART -->
<main>
  <div style="height: 300px;"></div>
  <?php if (isset($connection) && $connection): ?>
    <p style="color: green;">✅ Database Connected Successfully</p>
<?php else: ?>
    <p style="color: red;">❌ Database Connection Failed</p>
<?php endif; ?>
    <?php
        // If role_select posts (or redirects) to signup pages, this include will render the correct fragment.
        // No special handling needed besides ensuring $page is set correctly in index.php.
        $file = $pages[$page] ?? null;

if ($file && file_exists($file)) {
    include $file;
} else {
    echo "Page not found!";
}
    ?>
</main>

<hr>

<!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section">
        <div class="footer-logo">
          <span class="logo-kuet">KUET</span>
          <span class="logo-text">Debating Society</span>
          <span class="logo-kds">KDS</span>
        </div>
        <p>&copy; 2024 KUET Debating Society. All rights reserved.</p>
      </div>
      
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">Events</a></li>
          <li><a href="#">Debates</a></li>
          <li><a href="#">Membership</a></li>
          <li><a href="#">Visit</a></li>
          <li><a href="#">Resources</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Contact</h3>
        <ul>
          <li><i class="fa-solid fa-envelope"></i> kds@kuet.ac.bd</li>
          <li><i class="fa-solid fa-phone"></i> +880-xxx-xxx-xxx</li>
          <li><i class="fa-solid fa-map-marker-alt"></i> KUET Campus, Khulna</li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3>Follow Us</h3>
        <div class="social-links">
          <a href="https://www.facebook.com/groups/kds.kuet/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
          <a href="mailto:kds@kuet.ac.bd"><i class="fa-solid fa-envelope"></i></a>
        </div>
      </div>
    </div>
  </footer>

</body>

</html>