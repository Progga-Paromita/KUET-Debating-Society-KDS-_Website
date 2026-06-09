<?php include __DIR__ . "/../config/db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <a href="index.php?page=home"><i class="fa-solid fa-house"></i> Home</a>
        <?php if (!isset($_SESSION['role'])): ?>
        <a href="index.php?page=role_select"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
        <a href="index.php?page=login"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
    <?php endif; ?>
        <a href="#ask-question-section">
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

        <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] == "member"): ?>
            <a href="index.php?page=profile_member"><i class="fa-solid fa-user"></i></a>
        <?php elseif ($_SESSION['role'] == "admin"): ?>
            <a href="index.php?page=profile_admin"><i class="fa-solid fa-user"></i></a>
        <?php endif; ?>

        <a href="index.php?page=logout">Logout</a>
    <?php endif; ?>

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
                <li><a href="#upcoming">Upcoming Events</a></li>
                <li><a href="#past-events">Past Events</a></li>
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
                <li><a href="index.php?page=role_select">Join KDS</a></li>
                <li><a href="#membership">Member List</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <span class="nav-link" onclick="document.getElementById('resources-section').scrollIntoView({behavior:'smooth'})">Resources</span>
            </li>
            <li class="nav-item">
              <span class="nav-link">Visit</span>
              <ul class="dropdown">
                <li><a href="https://www.google.com/search?q=kuet&oq=KUET&gs_lcrp=EgZjaHJvbWUqDwgAECMYJxjjAhiABBiKBTIPCAAQIxgnGOMCGIAEGIoFMgwIARAuGCcYgAQYigUyBggCEEUYOzIHCAMQABiABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIHCAcQABiABDIHCAgQABiABDIHCAkQABiABNIBCTI5ODFqMGoxNagCCLACAfEFQiKhj5u3IrY&sourceid=chrome&ie=UTF-8#lpg=cid:CgIgAQ%3D%3D,ik:CAoSFkNJSE0wb2dLRUlDQWdJQzA5TmJXUVE%3D" target="_blank">Campus Tour</a></li>
                <li><a href="https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUqDwgAECMYJxjjAhiABBiKBTIPCAAQIxgnGOMCGIAEGIoFMgwIARAuGCcYgAQYigUyBggCEEUYOzIHCAMQABiABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIHCAcQABiABDIHCAgQABiABDIHCAkQABiABNIBCTI5ODFqMGoxNagCCLACAfEFQiKhj5u3IrY&um=1&ie=UTF-8&fb=1&gl=bd&sa=X&geocode=KeX2Dx3am_85MQzN7whpkjoS&daddr=Khulna+9203" target="_blank">Location</a></li>
              </ul>
            </li>
            
          </ul>
        </div>
      </nav>
    </div>
</header>
<hr>