<?php
$role = $_GET['role'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role == "admin") {

        // 🔴 send request instead of direct signup
        $sql = "INSERT INTO admin_requests(name,email,password) 
                VALUES('$name','$email','$password')";
        mysqli_query($conn, $sql);

        echo "Admin request sent. Wait for approval.";

    } else {

        $sql = "INSERT INTO member(name,email,password) 
                VALUES('$name','$email','$password')";
        mysqli_query($conn, $sql);

        echo "Member registered successfully!";
    }
}
?>
<section style="height: 300px;"></section>
<div class="member-register-wrap">
  <div class="member-register-card">
    <div class="member-register-header">
      <div class="member-register-badge">
        ✨ Signup Request
        <span style="font-size:12px; opacity:.85; font-family:'DM Mono', monospace;">Role: <?= htmlspecialchars($role) ?></span>
      </div>
      <h2>Signup as <?= htmlspecialchars($role) ?></h2>
      <div class="member-register-subtitle">
        Fill in your details below. <?= ($role === 'admin') ? 'Admin signups require approval.' : 'You can sign in immediately after signup.' ?>
      </div>
    </div>

    <?php if ($role === 'admin'): ?>
      <div class="member-register-alert member-register-alert-success">
        <i>🛡️</i>
        <div>
          <div class="member-register-alert-title">Admin Request</div>
          <ul class="member-register-alert-list">
            <li>Account will be created after admin approval.</li>
            <li>You’ll be redirected to admin panel after approval.</li>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <form class="member-register-form" method="POST">
      <div class="member-register-grid">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input id="name" name="name" placeholder="Your name" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Create a strong password" required>
        </div>
      </div>

      <div class="member-register-actions">
        <div class="member-register-hint">
          By signing up, you agree to follow the club’s rules and code of conduct.
        </div>

        <button class="btn btn-primary member-register-submit" type="submit">
          ➕ Signup
        </button>
      </div>
    </form>
  </div>
</div>
